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
     * @var InspectorInterface
     */
    protected $inspector;

    /**
     * Constructor.
     *
     * @param string             $jobId
     * @param InspectorInterface $inspector
     */
    public function __construct($jobId, InspectorInterface $inspector)
    {
        $this->jobId = $jobId;
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
     * Returns inspector.
     *
     * @return InspectorInterface
     */
    public function getInspector()
    {
        return $this->inspector;
    }
}
