<?php

/*
 * This file is part of the WhteRbtFileInspectionsBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FileInspectionsBundle\Command;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WhteRbt\FileInspectionsBundle\EventListener\Event\InspectionEvent;
use WhteRbt\FileInspectionsBundle\Events;
use WhteRbt\FileInspectionsBundle\Inspector\InspectorFactory;

class InspectAllCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('whte-rbt:file-inspections:inspect-all')
            ->setDescription('Inspects all jobs at once');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $inspectorFactory = new InspectorFactory($this->getNamespacesFromConfiguration());
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');

        foreach ($this->getJobsFromConfiguration() as $job => $jobConfig) {
            foreach ($jobConfig['inspections'] as $inspection => $inspectionConfig) {
                if (true === $inspectionConfig['active']) {
                    try {
                        $inspector = $inspectorFactory->createInspector(
                            $inspection,
                            $jobConfig['path'],
                            $jobConfig['filename'],
                            $inspectionConfig['attributes']
                        );
                        $inspector->inspect();
                        $eventDispatcher->dispatch(Events::INSPECTION_SUCCESS, new InspectionEvent($job, $jobConfig['info_level'], $inspector));
                        $style->success(sprintf('Inspection "%s" of "%s/%s" successfully.', $inspection, $jobConfig['info_level'], $jobConfig['path'], $jobConfig['filename']));
                    } catch (LogicException $e) {
                        $eventDispatcher->dispatch(Events::INSPECTION_ERROR, new InspectionEvent($job, $jobConfig['info_level'], $inspector));
                        $style->error(sprintf('Inspection "%s" of "%s/%s" failed.', $inspection, $jobConfig['path'], $jobConfig['filename']));
                    }
                }
            }
        }
    }

    /**
     * Returns namespaces from configuration.
     *
     * @return array
     */
    protected function getNamespacesFromConfiguration()
    {
        return $this->getContainer()->getParameter('whte_rbt_file_inspections.namespaces');
    }

    /**
     * Returns all jobs from configuration.
     *
     * @return array
     */
    protected function getJobsFromConfiguration()
    {
        return $this->getContainer()->getParameter('whte_rbt_file_inspections.jobs');
    }
}
