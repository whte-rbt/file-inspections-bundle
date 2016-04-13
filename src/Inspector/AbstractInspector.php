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

abstract class AbstractInspector implements InspectorInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($path, $filename, array $attributes)
    {
        $this->path = $path;
        $this->filename = $filename;
        $this->attributes = $this->mergeAttributes($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilenameWithPath()
    {
        return sprintf('%s/%s', $this->path, $this->filename);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function inspect();

    /**
     * {@inheritdoc}
     */
    abstract public function getAttributeDefaults();

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * Returns merged attributes.
     *
     * @param array $attributes
     *
     * @return array
     */
    protected function mergeAttributes(array $attributes)
    {
        return array_merge($this->getAttributeDefaults(), $attributes);
    }
}
