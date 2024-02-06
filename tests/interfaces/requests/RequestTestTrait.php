<?php

namespace Darling\RoadyRoutingUtilities\tests\interfaces\requests;

use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;

/**
 * The RequestTestTrait defines common tests for
 * implementations of the Request interface.
 *
 * @see Request
 *
 */
trait RequestTestTrait
{

    /**
     * @var Request $request
     *                              An instance of a
     *                              Request
     *                              implementation to test.
     */
    protected Request $request;

    /**
     * Set up an instance of a Request implementation to test.
     *
     * This method must also set the Request implementation instance
     * to be tested via the setRequestTestInstance() method.
     *
     * This method may also be used to perform any additional setup
     * required by the implementation being tested.
     *
     * @return void
     *
     * @example
     *
     * ```
     * protected function setUp(): void
     * {
     *     $this->setRequestTestInstance(
     *         new \Darling\RoadyRoutingUtilities\classes\requests\Request()
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the Request implementation instance to test.
     *
     * @return Request
     *
     */
    protected function requestTestInstance(): Request
    {
        return $this->request;
    }

    /**
     * Set the Request implementation instance to test.
     *
     * @param Request $requestTestInstance
     *                              An instance of an
     *                              implementation of
     *                              the Request
     *                              interface to test.
     *
     * @return void
     *
     */
    protected function setRequestTestInstance(
        Request $requestTestInstance
    ): void
    {
        $this->request = $requestTestInstance;
    }

}

