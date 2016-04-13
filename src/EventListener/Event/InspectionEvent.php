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
    protected $jobId;

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
     * @param string             $jobId
     * @param string             $infoLevel
     * @param InspectorInterface $inspector
     */
    public function __construct($jobId, $infoLevel, InspectorInterface $inspector)
    {
        $this->jobId = $jobId;
        $this->infoLevel = $infoLevel;
        $this->inspector = $inspector;
    }

    /**
     * Returns jobId.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
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
