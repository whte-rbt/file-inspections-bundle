<?php

namespace WhteRbt\FileInspectionsBundle\Inspector;

use DateTimeImmutable;
/**
 * Value object for inspection errors.
 *
 * This class represents an inspection error handled by the context. If an
 * inspection fails, an instance of this class is returned by the inspector.
 */
final class InspectionError
{
    /**
     * @var string
     */
    private $inspector;

    /**
     * @var string
     */
    private $message;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * Constructor.
     *
     * @param string $inspector
     * @param string $message
     */
    private function __construct($inspector, $message)
    {
        $this->inspector = $inspector;
        $this->message = $message;
        $this->date = new DateTimeImmutable();
    }

    /**
     * Creates a new inspection error (named constructor).
     *
     * @param string $inspector
     * @param string $message
     *
     * @return InspectionError
     */
    public static function create($inspector, $message)
    {
        return new self($inspector, $message);
    }

    /**
     * Returns error details as string.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s at %s: %s',
            $this->inspector,
            $this->date->format('Y-m-d H:i:s'),
            $this->message
        );
    }
}
