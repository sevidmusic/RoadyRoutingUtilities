<?php

namespace Darling\RoadyRoutingUtilities\classes\routers;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingFile;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText as SafeTextInstance;
use \Darling\PHPTextTypes\classes\strings\Text as TextInstance;
use \Darling\RoadyModuleUtilities\interfaces\configuration\ModuleRoutesJsonConfigurationReader;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleCSSRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleJSRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleOutputRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\determinators\RoadyModuleFileSystemPathDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\classes\paths\RelativePath as RelativePathInstance;
use \Darling\RoadyRoutingUtilities\classes\responses\Response as ResponseInstance;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;
use \Darling\RoadyRoutingUtilities\interfaces\responses\Response;
use \Darling\RoadyRoutingUtilities\interfaces\routers\Router as RouterInterface;

class Router implements RouterInterface
{

    /**
     * Instantiate a new Router instance.
     *
     * @param ListingOfDirectoryOfRoadyModules $listingOfDirectoryOfRoadyModules
     *                             A ListingOfDirectoryOfRoadyModules
     *                             instance for the directory that
     *                             contains the modules whose Routes
     *                             the Router will process.
     *
     * @param ModuleCSSRouteDeterminator $moduleCSSRouteDeterminator
     *                                   A ModuleCSSRouteDeterminator
     *                                   instance that will be used
     *                                   to determine the Routes to
     *                                   css files that are not
     *                                   defined by a module in a
     *                                   configuration file.
     *
     * @param ModuleJSRouteDeterminator $moduleJSRouteDeterminator
     *                                  A ModuleJSRouteDeterminator
     *                                  instance that will be used
     *                                  to determine the Routes to
     *                                  javascript files that are not
     *                                  defined by a module in a
     *                                  configuration file.
     *
     * @param ModuleOutputRouteDeterminator $moduleOutputRouteDeterminator
     *                                  A ModuleOutputRouteDeterminator
     *                                  instance that will be used
     *                                  to determine the Routes to
     *                                  php and html files that are
     *                                  not defined by a module in a
     *                                  configuration file.
     *
     * @param RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminator
     *                        A RoadyModuleFileSystemPathDeterminator
     *                        instance that will be used
     *                        to determine the paths
     *                        to the files and
     *                        directories defined by
     *                        a module.
     *
     * @param ModuleRoutesJsonConfigurationReader $moduleRoutesJsonConfigurationReader
     *             A ModuleRoutesJsonConfigurationReader
     *             instance that will be used read
     *             configuration files provided by
     *             modules that define Routes for specific
     *             Domain Authorities. These files will
     *             be named according to the following
     *             convention:
     *
     *             sub-domain.domain.top-level-domain.PORTNUMBER.json
     *                                                     |
     *                                            Actual port number
     *                                              must be an int.
     *
     */
    public function __construct(
        private ListingOfDirectoryOfRoadyModules $listingOfDirectoryOfRoadyModules,
        private ModuleCSSRouteDeterminator $moduleCSSRouteDeterminator,
        private ModuleJSRouteDeterminator $moduleJSRouteDeterminator,
        private ModuleOutputRouteDeterminator $moduleOutputRouteDeterminator,
        private RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminator,
        private ModuleRoutesJsonConfigurationReader $moduleRoutesJsonConfigurationReader,
    ) {}

    public function handleRequest(Request $request): Response
    {
        $respondingRoutes = [];
        foreach (
        $this->listingOfDirectoryOfRoadyModules
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
                    $this->moduleRoutesJsonConfigurationReader
                         ->determineConfiguredRoutes(
                             $request->url()
                                     ->domain()
                                     ->authority(),
                             $pathToRoadyModuleDirectory,
                             $this->roadyModuleFileSystemPathDeterminator
                         );
                $dynamicallyDeterminedCssRoutes =
                    $this->moduleCSSRouteDeterminator
                          ->determineCSSRoutes(
                              $pathToRoadyModuleDirectory
                          );
                $dynamicallyDeterminedJsRoutes =
                    $this->moduleJSRouteDeterminator
                         ->determineJSRoutes(
                             $pathToRoadyModuleDirectory
                         );
                $dynamicallyDeterminedOutputRoutes =
                    $this->moduleOutputRouteDeterminator
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
                        )
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
     * Determine if a module defines a configuration file for the
     * specified $request.
     *
     * Return true if it does, false otherwise.
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

    /**
     * Determine the path to the configuration file defined by the
     * specified module for the Authority implied by the specified
     * Request.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   Path to the directory
     *                                   of modules.
     *
     * @param Request $request The Request whose Authority
     *                         will correspond to the name
     *                         of the configuration file that
     *                         defines a module's manually
     *                         configured Routes.
     *
     *
     * @return PathToExistingFile
     *
     */
    private function determinePathToConfigurationFile(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        Request $request
    ): PathToExistingFile
    {
        return $this->roadyModuleFileSystemPathDeterminator
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

}

