<?php

use Darling\PHPTextTypes\classes\strings\SafeText;

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
        moduleName: new \Darling\PHPTextTypes\classes\strings\Name(
            new \Darling\PHPTextTypes\classes\strings\Text('hello-world'),
        ),
        nameCollection: new \Darling\PHPTextTypes\classes\collections\NameCollection(
            new \Darling\PHPTextTypes\classes\strings\Name(
                new \Darling\PHPTextTypes\classes\strings\Text('homepage'),
            ),
        ),
        namedPositionCollection: new \Darling\RoadyRoutes\classes\collections\NamedPositionCollection(
            new \Darling\RoadyRoutes\classes\identifiers\NamedPosition(
                new \Darling\RoadyRoutes\classes\identifiers\PositionName(
                    new \Darling\PHPTextTypes\classes\strings\Name(
                        new \Darling\PHPTextTypes\classes\strings\Text('TargetPositionName'),
                    )
                ),
                new \Darling\RoadyRoutes\classes\settings\Position(0),
            ),
        ),
        relativePath: new \Darling\RoadyRoutes\classes\paths\RelativePath(
            new \Darling\PHPTextTypes\classes\collections\SafeTextCollection(
                new SafeText(new \Darling\PHPTextTypes\classes\strings\Text('output')),
                new SafeText(new \Darling\PHPTextTypes\classes\strings\Text('homepage.html')),
            )
        ),
    ),
);

$response = new Darling\RoadyRoutingUtilities\classes\responses\Response(
    $specificRequest, $routeCollection
);

