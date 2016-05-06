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
use WhteRbt\FileInspectionsBundle\Context\DefaultContext;

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
        /** @var DefaultContext $context */
        $context = $this->getContainer()->get('whte_rbt_file_inspections.default_context');

        $context->execute();

        $style = new SymfonyStyle($input, $output);
        if (!$context->hasErrors()) {
            $style->success('All inspections within all jobs successfully executed.');
        } else {
            $style->error(sprintf('%d inspection error(s) within %d job(s) occurred during execution of the jobs. Please see Emails (if configured) for further information.',
                $context->countErroneousInspectors(),
                $context->countErroneousJobs()
            ));
        }
    }
}
