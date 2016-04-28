<?php

/*
 * This file is part of the WhteRbtFileInspectionsBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FileInspectionsBundle\EventListener\Event;

use Symfony\Component\EventDispatcher\Event;
use WhteRbt\FileInspectionsBundle\Inspector\InspectorInterface;

class InspectionEvent extends Event
{
    /**
     * @var string
     */
    protected $job;

    /**
     * @var string
     */
    private $infoLevel;

    /**
     * @var InspectorInterface
     */
    protected $inspector;

    /**
     * Constructor.
     *
     * @param string             $job
     * @param string             $infoLevel
     * @param InspectorInterface $inspector
     */
    public function __construct($job, $infoLevel, InspectorInterface $inspector)
    {
        $this->job = $job;
        $this->infoLevel = $infoLevel;
        $this->inspector = $inspector;
    }

    /**
     * Returns job.
     *
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Returns infoLevel.
     * 
     * @return string
     */
    public function getInfoLevel()
    {
        return $this->infoLevel;
    }

    /**
     * Returns inspector.
     *
     * @return InspectorInterface
     */
    public function getInspector()
    {
        return $this->inspector;
    }
}
