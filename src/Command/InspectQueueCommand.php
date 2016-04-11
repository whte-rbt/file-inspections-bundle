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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WhteRbt\FileInspectionsBundle\Inspector\InspectorFactory;

class InspectQueueCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('whte-rbt:file-inspections:inspect-queue')
            ->setDescription('Inspects whole queue of configured files');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $inspectorFactory = new InspectorFactory($this->getNamespacesFromConfiguration());

        foreach ($this->getQueueFromConfigration() as $file) {
            foreach ($file['inspections'] as $id => $inspection) {
                if (true === $inspection['active']) {
                    $inspector = $inspectorFactory->createInspector(
                        $id,
                        $file['path'],
                        $file['filename'],
                        $inspection['attributes']
                    );
                    $inspector->inspect();

                    $style->success(sprintf('Inspection "%s" of "%s/%s" successfully.', $id, $file['path'], $file['filename']));
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
     * Returns queue from configuration.
     *
     * @return array
     */
    protected function getQueueFromConfigration()
    {
        return $this->getContainer()->getParameter('whte_rbt_file_inspections.queue');
    }
}
