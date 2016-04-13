<?php

/*
 * This file is part of the WhteRbtFileInspectionsBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FileInspectionsBundle\Inspector;

use LogicException;
use Symfony\Component\Finder\Finder;

class DateInspector extends AbstractInspector
{
    /**
     * @var array
     */
    public static $validDays = [
        'today', 'yesterday',
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
        'last monday', 'last tuesday', 'last wednesday', 'last thursday', 'last friday', 'last saturday', 'last sunday',
    ];

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'DateInspector';
    }

    /**
     * {@inheritdoc}
     */
    public function inspect()
    {
        $finder = new Finder();

        $day = $this->getValidatedDayAttribute($this->attributes['day']);
        $startTime = $this->getValidatedStartTimeAttribute($this->attributes['startTime']);
        $endTime = $this->getValidatedEndTimeAttribute($this->attributes['endTime']);

        $foundFiles = $finder
            ->in($this->path)
            ->name($this->filename)
            ->date(sprintf('>= %s %s', $day, $startTime))
            ->date(sprintf('<= %s %s', $day, $endTime))
            ->count();

        if (1 != $foundFiles) {
            throw new LogicException(sprintf('The requested file "%s" in path "%s" does not have a valid date ("%s between %s and %s").',
                $this->filename,
                $this->path,
                $day,
                $startTime,
                $endTime
            ));
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeDefaults()
    {
        return [
            'day' => 'today',
            'startTime' => '00:00:00',
            'endTime' => '23:59:59',
        ];
    }

    /**
     * Returns validated day attribute.
     *
     * @param string $day
     *
     * @return string
     */
    protected function getValidatedDayAttribute($day)
    {
        if (!in_array(strtolower($day), self::$validDays)) {
            throw new LogicException(sprintf('The attribute "day" does not contain a valid value (%s)', implode(', ', self::$validDays)));
        }

        return $day;
    }

    /**
     * Returns validated start time attribute.
     *
     * @param string $startTime
     *
     * @return string
     */
    protected function getValidatedStartTimeAttribute($startTime)
    {
        if (!preg_match('/^([0-1]?[0-9]|[2][0-3]):([0-5][0-9])(:[0-5][0-9])?$/', $startTime)) {
            throw new LogicException('The attribute "startTime" does not contain a valid format (hh:mm[:ss])');
        }

        return $startTime;
    }

    /**
     * Returns validated end time attribute.
     *
     * @param string $endTime
     *
     * @return string
     */
    protected function getValidatedEndTimeAttribute($endTime)
    {
        if (!preg_match('/^([0-1]?[0-9]|[2][0-3]):([0-5][0-9])(:[0-5][0-9])?$/', $endTime)) {
            throw new LogicException('The attribute "endTime" does not contain a valid format (hh:mm[:ss])');
        }

        return $endTime;
    }
}
