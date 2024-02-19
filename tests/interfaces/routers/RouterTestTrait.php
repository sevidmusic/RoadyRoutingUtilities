<?php

namespace Darling\RoadyRoutingUtilities\tests\interfaces\routers;

use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory as PathToExistingDirectoryInstance;
use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingFile;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText as SafeTextInstance;
use \Darling\PHPTextTypes\classes\strings\Text as TextInstance;
use \Darling\RoadyModuleUtilities\classes\configuration\ModuleRoutesJsonConfigurationReader as ModuleRoutesJsonConfigurationReaderInstance;
use \Darling\RoadyModuleUtilities\classes\determinators\ModuleCSSRouteDeterminator as ModuleCSSRouteDeterminatorInstance;
use \Darling\RoadyModuleUtilities\classes\determinators\ModuleJSRouteDeterminator as ModuleJSRouteDeterminatorInstance;
use \Darling\RoadyModuleUtilities\classes\determinators\ModuleOutputRouteDeterminator as ModuleOutputRouteDeterminatorInstance;
use \Darling\RoadyModuleUtilities\classes\determinators\RoadyModuleFileSystemPathDeterminator as RoadyModuleFileSystemPathDeterminatorInstance;
use \Darling\RoadyModuleUtilities\classes\directory\listings\ListingOfDirectoryOfRoadyModules as ListingOfDirectoryOfRoadyModulesInstance;
use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules as PathToDirectoryOfRoadyModulesInstance;
use \Darling\RoadyModuleUtilities\interfaces\configuration\ModuleRoutesJsonConfigurationReader;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleCSSRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleJSRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleOutputRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\determinators\RoadyModuleFileSystemPathDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\classes\paths\RelativePath as RelativePathInstance;
use \Darling\RoadyRoutingUtilities\classes\responses\Response as ResponseInstance;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;
use \Darling\RoadyRoutingUtilities\interfaces\responses\Response;
use \Darling\RoadyRoutingUtilities\interfaces\routers\Router;
use \PHPUnit\Framework\Attributes\CoversClass;

/**
 * The RouterTestTrait defines common tests for implementations of
 * the Router interface.
 *
 * @see Router
 *
 */
#[CoversClass(Router::class)]
trait RouterTestTrait
{

    /**
     * @var Router $router An instance of a Router implementation
     *                     to test.
     */
    protected Router $router;

    /**
     * @var Request $testRequest An instance of a Request
     *                           to use for testing.
     */
    private Request $testRequest;

    /**
     * Set up an instance of a Router implementation to test.
     *
     * This method must set the Router implementation instance
     * to be tested via the setRouterTestInstance() method.
     *
     * This method must set the Request instance that will be
     * used to test the Router via the setTestRequest() method.
     *
     * This method may also be used to perform any additional setup
     * required by the implementation being tested.
     *
     * @return void
     *
     * @example
     *
     * ```
     * public function setUp(): void
     * {
     *     $testRequest = new RequestInstance($this->randomUrlString());
     *     $listingOfDirectoryOfRoadyModules = $this->listingOfDirectoryOfRoadyTestModules();
     *     $this->setTestRequest($testRequest);
     *     $this->setRouterTestInstance(
     *         new Router(
     *             $this->listingOfDirectoryOfRoadyTestModules(),
     *             $this->moduleCSSRouteDeterminator(),
     *             $this->moduleJSRouteDeterminator(),
     *             $this->moduleOutputRouteDeterminator(),
     *             $this->roadyModuleFileSystemPathDeterminator(),
     *             $this->moduleRoutesJsonConfigurationReader(),
     *         )
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the Router implementation instance to test.
     *
     * @return Router
     *
     */
    protected function routerTestInstance(): Router
    {
        return $this->router;
    }

    /**
     * Set the Router implementation instance to test.
     *
     * @param Router $routerTestInstance An instance of an
     *                                   implementation of
     *                                   the Router interface
     *                                   to test.
     *
     * @return void
     *
     */
    protected function setRouterTestInstance(
        Router $routerTestInstance
    ): void
    {
        $this->router = $routerTestInstance;
    }

    /**
     * Set the Request instance that will be used to test the
     * Router implementation being tested.
     *
     * @param Request $request The Request that should be used to test
     *                         the Router implementation instance
     *                         being tested.
     *
     * @return void
     *
     */
    protected function setTestRequest(Request $request): void
    {
        $this->testRequest = $request;
    }

    /**
     * Return the Request instance that will be used to test the
     * Router implementation being tested.
     *
     * @return Request
     *
     */
    protected function testRequest(): Request
    {
        return $this->testRequest;
    }

    /**
     * Determine if a module defins a configuration file for the
     * specified Request. Return true if it does, false otherwise.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The path to the Roady
     *                                   module directory where the
     *                                   configuration file is
     *                                   expected to be located.
     *
     * @param Request $request The Request the configuration file
     *                         would be defined for if it exists.
     *
     * @return bool
     *
     */
    private function configurationFileExistsForCurrentRequestsAuthority(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        Request $request
    ): bool
    {
        return str_replace(
            ':',
            '.',
            $request->url()->domain()->authority()->__toString()
        ) . '.json'
        ===
        $this->determinePathToConfigurationFile(
            $pathToRoadyModuleDirectory,
            $request
        )->name()->__toString();
    }

    private function determinePathToConfigurationFile(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        Request $request
    ): PathToExistingFile
    {
        return $this->roadyModuleFileSystemPathDeterminator()
                    ->determinePathToFileInModuleDirectory(
                        $pathToRoadyModuleDirectory,
                        new RelativePathInstance(
                            new SafeTextCollectionInstance(
                                new SafeTextInstance(
                                    new TextInstance(
                                        str_replace(
                                            ':',
                                            '.',
                                            $request->url()
                                                    ->domain()
                                                    ->authority()
                                                    ->__toString()
                                        ) . '.json'
                                    )
                                )
                            )
                        ),
                    );
    }

    /**
     * Deterine the Response that is expected to be returned by the
     * Router implementation instance being tested's handleRequest()
     * method if is provided the specified Request.
     *
     * @param Request $request A Request that will be used to
     *                         determine an appropriate Response.
     *
     * @return Response
     *
     */
    protected function expectedResponse(Request $request): Response
    {
        $respondingRoutes = [];
        foreach (
        $this->listingOfDirectoryOfRoadyTestModules()
             ->pathToRoadyModuleDirectoryCollection()
             ->collection()
            as
            $pathToRoadyModuleDirectory
        ) {
            if(
                $this->configurationFileExistsForCurrentRequestsAuthority(
                    $pathToRoadyModuleDirectory,
                    $request
                )
            ) {
                $manuallyConfiguredRoutes =
                    $this->moduleRoutesJsonConfigurationReader()
                         ->determineConfiguredRoutes(
                             $request->url()
                                     ->domain()
                                     ->authority(),
                             $pathToRoadyModuleDirectory,
                             $this->roadyModuleFileSystemPathDeterminator()
                         );
                $dynamicallyDeterminedCssRoutes =
                    $this->moduleCSSRouteDeterminator()
                          ->determineCSSRoutes(
                              $pathToRoadyModuleDirectory
                          );
                $dynamicallyDeterminedJsRoutes =
                    $this->moduleJSRouteDeterminator()
                         ->determineJSRoutes(
                             $pathToRoadyModuleDirectory
                         );
                $dynamicallyDeterminedOutputRoutes =
                    $this->moduleOutputRouteDeterminator()
                         ->determineOutputRoutes(
                             $pathToRoadyModuleDirectory
                         );
                $determinedRoutes = array_merge(
                    $manuallyConfiguredRoutes->collection(),
                    $dynamicallyDeterminedCssRoutes->collection(),
                    $dynamicallyDeterminedJsRoutes->collection(),
                    $dynamicallyDeterminedOutputRoutes->collection(),
                );
                foreach($determinedRoutes as $route) {
                    if(
                        in_array(
                            $request->name(),
                            $route->nameCollection()->collection()
                        )
                        ||
                        in_array(
                            new NameInstance(new TextInstance('global')),
                            $route->nameCollection()->collection()
                        ) # todo: check str_contains(global) for each name in collection istead of just checking for existtence of the name "global"
                    ) {
                        $respondingRoutes[] = $route;
                    }
                }
            }
        }
        return new ResponseInstance(
            $request,
            new RouteCollectionInstance(...$respondingRoutes)
        );
    }

    /**
     * Return the path to the directory of test modules that will
     * be used to test the Router.
     *
     * @return PathToDirectoryOfRoadyModules
     *
     */
    private function pathToDirectoryOfRoadyTestModules(): PathToDirectoryOfRoadyModules
    {
        $testModuleDirectoryPathString  = str_replace(
            'interfaces' . DIRECTORY_SEPARATOR . 'routers',
            'modules',
            __DIR__,
        );
        $testModuleDirectoryPathStringParts = explode(DIRECTORY_SEPARATOR, $testModuleDirectoryPathString);
        $arrayOfSafeText = [];
        foreach($testModuleDirectoryPathStringParts as $part) {
            if(!empty($part)) {
                $arrayOfSafeText[] = new SafeTextInstance(new TextInstance($part));
            }
        }
        return new PathToDirectoryOfRoadyModulesInstance(
            new PathToExistingDirectoryInstance(
                new SafeTextCollectionInstance(...$arrayOfSafeText))
        );
    }

    /**
     * Return the listing of the directory of test modules that will
     * be used to test the Router.
     *
     * @return ListingOfDirectoryOfRoadyModules
     *
     */
    protected function listingOfDirectoryOfRoadyTestModules(): ListingOfDirectoryOfRoadyModules
    {
        return new ListingOfDirectoryOfRoadyModulesInstance(
            $this->pathToDirectoryOfRoadyTestModules(),
        );
    }

    /**
     * Return a ModuleCSSRouteDeterminator instance to use for testing.
     *
     * @return ModuleCSSRouteDeterminator
     *
     */
    protected function moduleCSSRouteDeterminator(): ModuleCSSRouteDeterminator
    {
        return new ModuleCSSRouteDeterminatorInstance();
    }


    /**
     * Return a ModuleJSRouteDeterminator instance to use for testing.
     *
     * @return ModuleJSRouteDeterminator
     *
     */
    protected function moduleJSRouteDeterminator(): ModuleJSRouteDeterminator
    {
        return new ModuleJSRouteDeterminatorInstance();
    }


    /**
     * Return a ModuleOutputRouteDeterminator instance to use
     * for testing.
     *
     * @return ModuleOutputRouteDeterminator
     *
     */
    protected function moduleOutputRouteDeterminator(): ModuleOutputRouteDeterminator
    {
        return new ModuleOutputRouteDeterminatorInstance();
    }


    /**
     * Return a RoadyModuleFileSystemPathDeterminator instance to use
     * for testing.
     *
     * @return RoadyModuleFileSystemPathDeterminator
     *
     */
    protected function roadyModuleFileSystemPathDeterminator(): RoadyModuleFileSystemPathDeterminator
    {
        return new RoadyModuleFileSystemPathDeterminatorInstance();
    }


    /**
     * Return a ModuleRoutesJsonConfigurationReader instance to use for testing.
     *
     * @return ModuleRoutesJsonConfigurationReader
     *
     */
    protected function moduleRoutesJsonConfigurationReader(): ModuleRoutesJsonConfigurationReader
    {
        return new ModuleRoutesJsonConfigurationReaderInstance();
    }

    /**
     * Test that handleRequest() returns the expected Response based
     * on a given Request.
     *
     * @return void
     *
     */
    public function test_handleRequest_returns_the_expected_Response(): void
    {
        $this->assertEquals(
            $this->expectedResponse($this->testRequest()),
            $this->routerTestInstance()->handleRequest($this->testRequest()),
            $this->testFailedMessage(
                $this->routerTestInstance(),
                'handleRequest',
                'must return the expected Response',
            )
        );
    }

    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;
    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;

}
