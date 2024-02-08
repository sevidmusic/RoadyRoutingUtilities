<?php

namespace Darling\RoadyRoutingUtilities\tests\interfaces\responses;

use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \Darling\RoadyRoutingUtilities\classes\requests\Request as RequestInstance;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;
use \Darling\RoadyRoutingUtilities\interfaces\responses\Response;

/**
 * The ResponseTestTrait defines common tests for implementations of
 * the Response interface.
 *
 * @see Response
 *
 */
trait ResponseTestTrait
{

    private RouteCollection $routeCollection;

    private Request $request;

    /**
     * @var Response $response An instance of a Response
     *                         implementation to test.
     */
    protected Response $response;

    /**
     * Set up an instance of a Response implementation to test.
     *
     * This method must set the Response implementation instance
     * to be tested via the setResponseTestInstance() method.
     *
     * This method may also be used to perform any additional setup
     * required by the implementation being tested.
     *
     * @return void
     *
     * @example
     *
     * ```
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the Response implementation instance to test.
     *
     * @return Response
     *
     */
    protected function responseTestInstance(): Response
    {
        return $this->response;
    }

    /**
     * Set the Response implementation instance to test.
     *
     * @param Response $responseTestInstance An instance of an
     *                                       implementation of
     *                                       the Response interface
     *                                       to test.
     *
     * @return void
     *
     */
    protected function setResponseTestInstance(
        Response $responseTestInstance
    ): void
    {
        $this->response = $responseTestInstance;
    }

    /**
     * Set the Request instance that is expected to be returned by the
     * Request implementation being tested's request() method.
     *
     * @param Request $request The Request instance that is expected
     *                         to be returned by the Request
     *                         implementation being tested's
     *                         request() method.
     *
     * @return void
     *
     */
    protected function setExpectedRequest(Request $request) : void
    {
        $this->request = $request;
    }

    /**
     * Return the Request instance that is expected to be returned by
     * the Request implementation being tested's request() method.
     *
     * @return Request
     *
     */
    protected function expectedRequest() : Request
    {
        return $this->request;
    }

    /**
     * Set the RouteCollection instance that is expected to be
     * returned by the RouteCollection implementation being tested's
     * routeCollection() method.
     *
     * @param RouteCollection $routeCollection The RouteCollection
     *                                         instance that is
     *                                         expected to be returned
     *                                         by the RouteCollection
     *                                         implementation being
     *                                         tested's
     *                                         routeCollection() method.
     *
     * @return void
     *
     */
    protected function setExpectedRouteCollection(RouteCollection $routeCollection) : void
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * Return the RouteCollection instance that is expected to be
     * returned by the RouteCollection implementation being tested's
     * routeCollection() method.
     *
     *
     * @return RouteCollection
     *
     */
    protected function expectedRouteCollection() : RouteCollection
    {
        return $this->routeCollection;
    }

    /**
     * Test that the request() method returns the expected Request.
     *
     * @return void
     *
     * @covers Request->request()
     *
     */
    public function test_request_returns_expected_request(): void
    {
        $this->assertEquals(
            $this->expectedRequest(),
            $this->responseTestInstance()->request(),
            $this->testFailedMessage(
                $this->responseTestInstance(),
                'request',
                'request() must return the expected Request: ' . $this->expectedRequest()->url()->__toString(),
            )
        );
    }

    /**
     * Test that the routeCollection() method returns the expected
     * RouteCollection.
     *
     * @return void
     *
     * @covers Request->routeCollection()
     *
     */
    public function test_routeCollection_returns_expected_routeCollection(): void
    {
        $this->assertEquals(
            $this->expectedRouteCollection(),
            $this->responseTestInstance()->routeCollection(),
            $this->testFailedMessage(
                $this->responseTestInstance(),
                'routeCollection',
                'routeCollection() must return the expected RouteCollection.',
            )
        );
    }
    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;
    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;

}

