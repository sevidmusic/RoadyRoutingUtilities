<?php

namespace Darling\RoadyRoutingUtilities\interfaces\requests;

use Darling\PHPTextTypes\interfaces\strings\Name;

/**
 * Description of this interface.
 *
 * @example
 *
 * ```
 *
 * ```
 */
interface Request
{


    /**
     * Return the Name of the Request.
     *
     * @return Name
     *
     */
    public function name(): Name;

}

