<?php

use Darling\RoadyRoutingUtilities\classes\responses\Response;

require(
    str_replace(
        'tests' . DIRECTORY_SEPARATOR . 'integration',
        'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
        __DIR__
    )
);

$specificRequest = new \Darling\RoadyRoutingUtilities\classes\requests\Request(
    'http://www.example.com:8080/path?query#fragment'
);

$routeCollection = new \Darling\RoadyRoutes\classes\collections\RouteCollection(
    new \Darling\RoadyRoutes\classes\routes\Route(
        new \Darling\PHPTextTypes\classes\strings\Name(
            new \Darling\PHPTextTypes\classes\strings\Text('route-name'),
        ),
        new \Darling\PHPTextTypes\classes\collections\NameCollection(
            new \Darling\PHPTextTypes\classes\strings\Name(
                new \Darling\PHPTextTypes\classes\strings\Text('route-name'),
            ),
        ),
        new \Darling\RoadyRoutes\classes\collections\NamedPositionCollection(
            new \Darling\RoadyRoutes\classes\identifiers\NamedPosition(
                new \Darling\RoadyRoutes\classes\identifiers\PositionName(
                    new \Darling\PHPTextTypes\classes\strings\Name(
                        new \Darling\PHPTextTypes\classes\strings\Text('route-name'),
                    )
                ),
                new \Darling\RoadyRoutes\classes\settings\Position(0),
            ),
        ),
        new \Darling\RoadyRoutes\classes\paths\RelativePath(
            new \Darling\PHPTextTypes\classes\collections\SafeTextCollection()
        ),
    ),
);

$response = new Response($specificRequest, $routeCollection);

var_dump(
    [
        'Request' => $response->request()->url()->__toString(),
        'Number of Routes' => count($response->routeCollection()->collection()),
    ],
);

