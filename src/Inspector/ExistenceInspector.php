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

class ExistenceInspector extends AbstractInspector
{
    /**
     * {@inheritdoc}
     */
    public function inspect()
    {
        $finder = new Finder();

        $foundFiles = $finder
            ->in($this->path)
            ->name($this->filename)
            ->count();

        if (1 != $foundFiles) {
            throw new LogicException(sprintf('The requested file "%s" in path "%s" does not exist.', $this->filename, $this->path));
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeDefaults()
    {
        return [];
    }
}
