<?php

namespace Darling\RoadyRoutingUtilities\tests\classes\requests;

use \Darling\RoadyRoutingUtilities\classes\requests\Request;
use \Darling\RoadyRoutingUtilities\tests\RoadyRoutingUtilitiesTest;
use \Darling\RoadyRoutingUtilities\tests\interfaces\requests\RequestTestTrait;
use \PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Request::class)]
class RequestTest extends RoadyRoutingUtilitiesTest
{

    /**
     * The RequestTestTrait defines common tests for implementations
     * of the Darling\RoadyRoutingUtilities\interfaces\requests\Request
     * interface.
     *
     * @see RequestTestTrait
     *
     */
    use RequestTestTrait;

    public function setUp(): void
    {
        $urlString = $this->randomUrlString();
        $this->setTestUrlString($urlString);
        $this->setRequestTestInstance(
            new Request($urlString)
        );
    }

}

