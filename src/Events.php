<?php

/*
 * This file is part of the WhteRbtFileInspectionsBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FileInspectionsBundle;

final class Events
{
    /**
     * This event is thrown after a successful inspection.
     */
    const INSPECTION_SUCCESS = 'whte_rbt_file_inspections.inspection_success';

    /**
     * This event is thrown after an erroneous inspection.
     */
    const INSPECTION_ERROR = 'whte_rbt_file_inspections.inspection_error';
}
