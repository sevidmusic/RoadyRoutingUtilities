<?php

namespace Darling\RoadyRoutingUtilities\interfaces\requests;

use Darling\PHPTextTypes\interfaces\strings\Name;
use Darling\PHPWebPaths\interfaces\paths\Url;

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
     * Return Request's Name.
     *
     * @return Name
     *
     */
    public function name(): Name;


    /**
     * Return the Request's Url.
     */
    public function url(): Url;

}

