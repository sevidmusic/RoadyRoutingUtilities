<?php

namespace Darling\RoadyRoutingUtilities\interfaces\responses;

use Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use Darling\RoadyRoutingUtilities\interfaces\requests\Request;


/**
 * Description of this interface.
 *
 * @example
 *
 * ```
 *
 * ```
 */
interface Response
{

    public function request(): Request;

    public function routeCollection(): RouteCollection;

}

