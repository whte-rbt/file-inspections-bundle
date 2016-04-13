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

class ContentInspector extends AbstractInspector
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
        return 'ContentInspector';
    }

    /**
     * {@inheritdoc}
     */
    public function inspect()
    {
        $finder = new Finder();

        $day = $this->getValidatedDayAttribute($this->attributes['day']);
        $pattern = $this->getParsedPattern($this->attributes['pattern'], $day);

        $foundFiles = $finder
            ->in($this->path)
            ->name($this->filename)
            ->contains($pattern)
            ->count();

        if (1 != $foundFiles) {
            throw new LogicException(sprintf('The requested file "%s" in path "%s" does not contains the given pattern ("%s").',
                $this->filename,
                $this->path,
                $pattern
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
     * Returns parsed pattern.
     *
     * @param string $pattern
     * @param string $day
     */
    protected function getParsedPattern($pattern, $day)
    {
        preg_match('#(\{\{){1}(.*)(\}\}){1}#', $pattern, $matches);
        $regexPattern = $matches[0];
        $date = date($matches[2], strtotime($day));

        return str_replace($regexPattern, $date, $pattern);
    }
}
