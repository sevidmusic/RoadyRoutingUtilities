# RoadyRoutingUtilities

The RoadyRoutingUtilities library provides the classes responsible
for handling routing for the [Roady](https://github.com/sevidmusic/roady)
`php` framework.

Though it is designed for use with the [Roady](https://github.com/sevidmusic/roady)
`php` framework, this library can be used on it's own.


# Installation

```sh
composer require darling/roady-routing-utilities
```

# Classes

### `Darling\RoadyRoutingUtilities\classes\requests\Request`

A Request represents a request to a server.

For example, to define a Request that represents the current request
to a server instantiate a new Request without any arguments:

```php
$currentRequest = new \Darling\RoadyRoutingUtilities\classes\requests\Request();
```

Alternatively, a Request may be instantiated with an optional url
`string` such as `http://example.com:8080?query#fragment` if a
specific request to a server is to be represented.

For example:

```php
$specificRequest = new \Darling\RoadyRoutingUtilities\classes\requests\Request(
    'http://www.example.com:8080/path?query#fragment'
);
```

### `Darling\RoadyRoutingUtilities\classes\responses\Response`

A Response represents the relationship between a Request and collection
of Routes that should be served in response to that Request.

For example, to define a Response for a specific Request to a server:

```php
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
```

### `Darling\RoadyRoutingUtilities\classes\routers\Router`

A Router can accept a Request and return an appropriate Response for
that Request.

For example:

```php
$specificRequest = new \Darling\RoadyRoutingUtilities\classes\requests\Request(
    'http://www.example.com:8080/path?query#fragment'
);

$router = new \Darling\RoadyRoutingUtilities\classes\routers\Router(
    new \Darling\RoadyModuleUtilities\classes\directory\listings\ListingOfDirectoryOfRoadyModules(
        new \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules(
            new \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory(
                new \Darling\PHPTextTypes\classes\collections\SafeTextCollection(
                    new \Darling\PHPTextTypes\classes\strings\SafeText(
                        new \Darling\PHPTextTypes\classes\strings\Text('path'),
                    ),
                    new \Darling\PHPTextTypes\classes\strings\SafeText(
                        new \Darling\PHPTextTypes\classes\strings\Text('to'),
                    ),
                    new \Darling\PHPTextTypes\classes\strings\SafeText(
                        new \Darling\PHPTextTypes\classes\strings\Text('directory'),
                    ),
                    new \Darling\PHPTextTypes\classes\strings\SafeText(
                        new \Darling\PHPTextTypes\classes\strings\Text('of'),
                    ),
                    new \Darling\PHPTextTypes\classes\strings\SafeText(
                        new \Darling\PHPTextTypes\classes\strings\Text('roady'),
                    ),
                    new \Darling\PHPTextTypes\classes\strings\SafeText(
                        new \Darling\PHPTextTypes\classes\strings\Text('modules'),
                    ),
                )
            ),
        ),
    ),
    new \Darling\RoadyModuleUtilities\classes\determinators\ModuleCSSRouteDeterminator(),
    new \Darling\RoadyModuleUtilities\classes\determinators\ModuleJSRouteDeterminator(),
    new \Darling\RoadyModuleUtilities\classes\determinators\ModuleOutputRouteDeterminator(),
    new \Darling\RoadyModuleUtilities\classes\determinators\RoadyModuleFileSystemPathDeterminator(),
    new \Darling\RoadyModuleUtilities\classes\configuration\ModuleRoutesJsonConfigurationReader(),
);

$response = $router->handleRequest($specificRequest);
```

As a final example, the following is a snippet from [Roady's](https://github.com/sevidmusic/roady)
`index.php` demonstrating how a Request, Response, and
Router are used in practice:

```php
$currentRequest = new RequestInstance();
$roadyModuleFileSystemPathDeterminator =
    new RoadyModuleFileSystemPathDeterminatorInstance();

$router = new RouterInstance(
    new ListingOfDirectoryOfRoadyModulesInstance(
        RoadyAPI::pathToDirectoryOfRoadyModules()
    ),
    new ModuleCSSRouteDeterminatorInstance(),
    new ModuleJSRouteDeterminatorInstance(),
    new ModuleOutputRouteDeterminatorInstance(),
    $roadyModuleFileSystemPathDeterminator,
    new ModuleRoutesJsonConfigurationReaderInstance(),
);

$response = $router->handleRequest($currentRequest);

$roadyUI = new RoadyUI(
    RoadyAPI::pathToDirectoryOfRoadyModules(),
    new RouteCollectionSorterInstance(),
    $roadyModuleFileSystemPathDeterminator,
);

echo $roadyUI->render($response);

```

