<?php

namespace Darling\RoadyRoutingUtilities\interfaces\responses;

use Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use Darling\RoadyRoutingUtilities\interfaces\requests\Request;


/**
 * A Response is composed of a Request and collection of Routes
 * that should be served in response to that Request.
 *
 */
interface Response
{

    /**
     * The Request that this Response responds to.
     *
     * @return Request
     *
     */
    public function request(): Request;

    /**
     * The Routes that should be served with this Response.
     *
     * @return RouteCollection
     *
     */
    public function routeCollection(): RouteCollection;

}

