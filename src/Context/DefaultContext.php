<?php

/*
 * This file is part of the WhteRbtFileInspectionsBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FileInspectionsBundle\Context;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use WhteRbt\FileInspectionsBundle\EventListener\Event\InspectionEvent;
use WhteRbt\FileInspectionsBundle\Events;
use WhteRbt\FileInspectionsBundle\Inspector\InspectionError;
use WhteRbt\FileInspectionsBundle\Inspector\InspectorFactory;

class DefaultContext implements ContextInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var array
     */
    protected $namespaces = [];

    /**
     * @var array
     */
    protected $jobs = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Constructor.
     *
     * By default, the whole bundle configuration is loaded within the context.
     * To limit the execution to one or more dedicated jobs, the method "limitTo()"
     * is used.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param array                    $configuration
     */
    public function __construct(EventDispatcherInterface $dispatcher, $configuration)
    {
        $this->dispatcher = $dispatcher;
        $this->namespaces = $configuration['namespaces'];
        $this->jobs = $configuration['jobs'];
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $inspectorFactory = new InspectorFactory($this->namespaces);
        foreach ($this->jobs as $job => $jobConfig) {
            foreach ($jobConfig['inspections'] as $inspection => $inspectionConfig) {
                if (true === $inspectionConfig['active']) {
                    $inspector = $inspectorFactory->createInspector(
                        $inspection,
                        $jobConfig['path'],
                        $jobConfig['filename'],
                        $inspectionConfig['attributes']
                    );

                    $inspection = $inspector->inspect();

                    if ($inspection instanceof InspectionError) {
                        $this->dispatcher->dispatch(Events::INSPECTION_ERROR, new InspectionEvent($job, $jobConfig['info_level'], $inspector));
                        $this->errors[$job][] = $inspection;
                    } else {
                        $this->dispatcher->dispatch(Events::INSPECTION_SUCCESS, new InspectionEvent($job, $jobConfig['info_level'], $inspector));
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function limitTo(array $jobs)
    {
        $selectedJobs = [];
        foreach ($this->jobs as $job => $jobConfig) {
            if (in_array($job, $jobs)) {
                $selectedJobs[$job] = $jobConfig;
            }
        }
        $this->jobs = $selectedJobs;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->errors = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasErrors()
    {
        return ($this->countErroneousJobs() > 0) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function countErroneousJobs()
    {
        return count($this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function countErroneousInspectors()
    {
        $amount = 0;
        foreach ($this->getErrors() as $job) {
            $amount = $amount + count($job);
        }

        return $amount;
    }
}
