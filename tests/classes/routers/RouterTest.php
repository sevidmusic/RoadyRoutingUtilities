<?php

namespace Darling\RoadyRoutingUtilities\tests\classes\routers;

use \Darling\RoadyRoutingUtilities\classes\requests\Request as RequestInstance;
use \Darling\RoadyRoutingUtilities\classes\routers\Router;
use \Darling\RoadyRoutingUtilities\tests\RoadyRoutingUtilitiesTest;
use \Darling\RoadyRoutingUtilities\tests\interfaces\routers\RouterTestTrait;
use \PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Router::class)]
class RouterTest extends RoadyRoutingUtilitiesTest
{

    /**
     * The RouterTestTrait defines common tests for implementations
     * of the Darling\RoadyRoutingUtilities\interfaces\routers\Router
     * interface.
     *
     * @see RouterTestTrait
     *
     */
    use RouterTestTrait;

    public function setUp(): void
    {
        $testRequest = new RequestInstance($this->randomUrlString());
        $listingOfDirectoryOfRoadyModules = $this->listingOfDirectoryOfRoadyModules();
        $this->setTestRequest($testRequest);
        $this->setRouterTestInstance(
            new Router(
                $this->listingOfDirectoryOfRoadyModules(),
                $this->moduleCSSRouteDeterminator(),
                $this->moduleJSRouteDeterminator(),
                $this->moduleOutputRouteDeterminator(),
                $this->roadyModuleFileSystemPathDeterminator(),
                $this->moduleRoutesJsonConfigurationReader(),
            )
        );
    }
}

