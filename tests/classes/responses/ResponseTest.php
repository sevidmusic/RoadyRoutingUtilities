<?php

namespace Darling\RoadyRoutingUtilities\tests\classes\responses;

use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutingUtilities\classes\requests\Request as RequestInstance;
use \Darling\RoadyRoutingUtilities\classes\responses\Response;
use \Darling\RoadyRoutingUtilities\tests\RoadyRoutingUtilitiesTest;
use \Darling\RoadyRoutingUtilities\tests\interfaces\responses\ResponseTestTrait;

class ResponseTest extends RoadyRoutingUtilitiesTest
{

    /**
     * The ResponseTestTrait defines
     * common tests for implementations of the
     * Darling\RoadyRoutingUtilities\interfaces\responses\Response
     * interface.
     *
     * @see ResponseTestTrait
     *
     */
    use ResponseTestTrait;

    public function setUp(): void
    {
        $urlString = $this->randomUrlString();
        $request = new RequestInstance($urlString);
        $routeCollection = new RouteCollectionInstance();
        $this->setExpectedRequest($request);
        $this->setExpectedRouteCollection($routeCollection);
        $this->setResponseTestInstance(
            new Response($request, $routeCollection)
        );
    }
}

