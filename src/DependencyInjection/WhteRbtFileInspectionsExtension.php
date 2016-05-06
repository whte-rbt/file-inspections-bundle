<?php

/*
 * This file is part of the WhteRbtFileInspectionsBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FileInspectionsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WhteRbtFileInspectionsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Set config parameters
        $container->setParameter('whte_rbt_file_inspections', $config);
        $container->setParameter('whte_rbt_file_inspections.mailer', $config['mailer']);
        $container->setParameter('whte_rbt_file_inspections.namespaces', $config['namespaces']);
        $container->setParameter('whte_rbt_file_inspections.jobs', $config['jobs']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return 'http://whte-rbt.github.io/schema/dic/file-inspections';
    }
}
