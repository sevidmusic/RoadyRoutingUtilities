<?php

namespace Darling\RoadyRoutingUtilities\classes\responses;

use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;
use \Darling\RoadyRoutingUtilities\interfaces\responses\Response as ResponseInterface;

class Response implements ResponseInterface
{

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

