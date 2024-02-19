<?php

namespace Darling\RoadyRoutingUtilities\interfaces\requests;

use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\PHPWebPaths\interfaces\paths\Url;

/**
 * A Request represents a request to a server.
 *
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
     *
     * @return Url
     *
     */
    public function url(): Url;

}

