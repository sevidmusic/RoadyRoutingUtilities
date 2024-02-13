<?php

namespace Darling\RoadyRoutingUtilities\classes\responses;

use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;
use \Darling\RoadyRoutingUtilities\interfaces\responses\Response as ResponseInterface;

class Response implements ResponseInterface
{

    /**
     * Instantiate a new Response instance using the specified
     * $request and $routeCollection.
     *
     * The $routeCollection should contain the Routes that are meant
     * to be served in response to the specified $request.
     *
     * @param Request $request The Request instance that represents
     *                         the request that should trigger the
     *                         server to reply with this response.
     *
     * @param RouteCollection $routeCollection The Routes that should
     *                                         be included in this
     *                                         response.
     *
     */
    public function __construct(private Request $request, private RouteCollection $routeCollection) { }

    public function request(): Request
    {
        return $this->request;
    }

    public function routeCollection(): RouteCollection
    {
        return $this->routeCollection;
    }

}

