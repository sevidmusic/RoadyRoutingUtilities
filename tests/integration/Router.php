<?php

require(
    str_replace(
        'tests' . DIRECTORY_SEPARATOR . 'integration',
        'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
        __DIR__
    )
);

$roadyRoutingUtilitiesTestingAPI = new \Darling\RoadyRoutingUtilities\tests\RoadyRoutingUtilitiesTest('RouterIntegrationTest');

$specificRequest = new \Darling\RoadyRoutingUtilities\classes\requests\Request(
    $roadyRoutingUtilitiesTestingAPI->randomUrlString()
);

$router = new Darling\RoadyRoutingUtilities\classes\routers\Router(
    new \Darling\RoadyModuleUtilities\classes\directory\listings\ListingOfDirectoryOfRoadyModules(
        $roadyRoutingUtilitiesTestingAPI->pathToDirectoryOfRoadyTestModules(),
    ),
    new \Darling\RoadyModuleUtilities\classes\determinators\ModuleCSSRouteDeterminator(),
    new \Darling\RoadyModuleUtilities\classes\determinators\ModuleJSRouteDeterminator(),
    new \Darling\RoadyModuleUtilities\classes\determinators\ModuleOutputRouteDeterminator(),
    new \Darling\RoadyModuleUtilities\classes\determinators\RoadyModuleFileSystemPathDeterminator(),
    new \Darling\RoadyModuleUtilities\classes\configuration\ModuleRoutesJsonConfigurationReader(),
);

var_dump(
    [
        'Request: ' => $router->handleRequest($specificRequest)->request()->url()->__toString(),
        'Number of Routes included in Response: ' => count(
            $router->handleRequest($specificRequest)->routeCollection()->collection()
        ),
    ]
);

