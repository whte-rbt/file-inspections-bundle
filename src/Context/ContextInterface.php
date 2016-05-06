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

interface ContextInterface
{
    /**
     * Executes job list.
     */
    public function execute();

    /**
     * Limits the context only to dedicated jobs.
     *
     * @param array $jobs
     *
     * @return ContextInterface
     */
    public function limitTo(array $jobs);

    /**
     * Resets context for a repeated run.
     *
     * @return ContextInterface
     */
    public function reset();

    /**
     * Returns true if context got errors.
     *
     * @return bool
     */
    public function hasErrors();

    /**
     * Returns raw array of inspection errors.
     *
     * @return [InspectionError]
     */
    public function getErrors();

    /**
     * Returns amount of erroneous jobs.
     *
     * @return int
     */
    public function countErroneousJobs();

    /**
     * Returns amount of erroneous inspectors.
     *
     * @return int
     */
    public function countErroneousInspectors();
}
