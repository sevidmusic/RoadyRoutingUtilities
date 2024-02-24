<?php

namespace Darling\RoadyRoutingUtilities\interfaces\requests;

use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\PHPWebPaths\interfaces\paths\Url;

/**
 * A Request represents a request to a server.
 *
 * A Request has a Name, and a Url.
 *
 * A Request's Name will either be based on value of the request
 * query parameter that is assigned to the Request's Url, the value
 * of $_POST['request'], or the value of $_GET['request'].
 *
 * If the Url is not assigned a query parameter named request, and
 * nither $_POST['request'] or $_GET['request'] is set, then
 * the Request's Name will be homepage.
 *
 * Note: The Url's request query parameter will always be preferred
 * if it is set, then $_POST['request'], then $_GET['request'].
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

