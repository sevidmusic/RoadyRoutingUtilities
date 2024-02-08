<?php

namespace Darling\RoadyRoutingUtilities\classes\responses;

use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutingUtilities\interfaces\responses\Response as ResponseInterface;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;

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

