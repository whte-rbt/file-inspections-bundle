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

class InspectorFactory
{
    /**
     * @var array
     */
    protected $namespaces = [];

    /**
     * Constructor.
     *
     * @param array $namespaces
     */
    public function __construct(array $namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * Returns an inspector by id.
     *
     * @param string $id
     * @param string $path
     * @param string $filename
     * @param array  $attributes
     *
     * @return InspectorInterface
     *
     * @throws LogicException
     */
    public function createInspector($id, $path, $filename, array $attributes)
    {
        $className = $this->getClassNameById($id);

        foreach ($this->namespaces as $namespace) {
            $fullQualifiedClassName = sprintf('%s\%s', $namespace, $className);

            if (class_exists($fullQualifiedClassName)) {
                $inspector = new $fullQualifiedClassName($path, $filename, $attributes);
                if ($inspector instanceof InspectorInterface) {
                    return $inspector;
                }
            }
        }

        throw new LogicException(sprintf('No inspector "%s" found in the configured namespaces.', $className));
    }

    /**
     * Returns class name by id.
     *
     * @param int $id
     *
     * @return string
     */
    protected function getClassNameById($id)
    {
        $classNameParts = explode('_', $id);
        foreach ($classNameParts as $key => $part) {
            $classNameParts[$key] = ucwords($part);
        }

        return sprintf('%sInspector', implode('', $classNameParts));
    }
}
