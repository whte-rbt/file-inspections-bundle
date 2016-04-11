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

interface InspectorInterface
{
    /**
     * Constructor.
     *
     * @param string $path
     * @param string $filename
     * @param array  $attributes
     */
    public function __construct($path, $filename, array $attributes);

    /**
     * Inspects the file $filename in given $path.
     *
     * @throws LogicException;
     */
    public function inspect();

    /**
     * Returns attribute defaults for the inspector.
     *
     * @return array
     */
    public function getAttributeDefaults();
}
