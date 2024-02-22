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

var_dump(
    [
        'Current Request:',
        '__toString' => $currentRequest->url()->__toString(),
        'domain' => $currentRequest->url()->domain()->__toString(),
        'path' => $currentRequest->url()->path()?->__toString(),
        'query' => $currentRequest->url()->query()?->__toString(),
        'fragment' => $currentRequest->url()->fragment()?->__toString(),
    ]
);
```

Alternatively, a Request may be instantiated with an optional url
`string` such as `http://example.com:8080?query#fragment` if a
specific request to a server is to be represented.

For example:

```php
$specificRequest = new \Darling\RoadyRoutingUtilities\classes\requests\Request(
    'http://www.example.com:8080/path?query#fragment'
);

var_dump(
    [
        'Specific Request:',
        '__toString' => $specificRequest->url()->__toString(),
        'domain' => $specificRequest->url()->domain()->__toString(),
        'path' => $specificRequest->url()->path()?->__toString(),
        'query' => $specificRequest->url()->query()?->__toString(),
        'fragment' => $specificRequest->url()->fragment()?->__toString(),
    ]
);
```

Output:

```php
array(6) {
  [0]=>
  string(16) "Current Request:"
  ["__toString"]=>
  string(19) "http://localhost:80"
  ["domain"]=>
  string(19) "http://localhost:80"
  ["path"]=>
  NULL
  ["query"]=>
  NULL
  ["fragment"]=>
  NULL
}
array(6) {
  [0]=>
  string(17) "Specific Request:"
  ["__toString"]=>
  string(47) "http://www.example.com:8080/path?query#fragment"
  ["domain"]=>
  string(27) "http://www.example.com:8080"
  ["path"]=>
  string(5) "/path"
  ["query"]=>
  string(5) "query"
  ["fragment"]=>
  string(8) "fragment"
}
```

### `Darling\RoadyRoutingUtilities\classes\responses\Response`

A Response is composed of a Request and collection of Routes that
should be served in response to that Request.

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

var_dump(
    [
        'Request' => $response->request()->url()->__toString(),
        'Number of Routes' => count($response->routeCollection()->collection()),
    ],
);
```

Output:

```php
array(3) {
  ["Request"]=>
  string(47) "http://www.example.com:8080/path?query#fragment"
  ["Number of Routes"]=>
  int(1)
  ["First Route"]=>
  array(3) {
    ["module name"]=>
    string(11) "hello-world"
    ["relative path"]=>
    string(20) "output/homepage.html"
    ["responds to requests: "]=>
    string(15) "[homepage, ...]"
  }
}
```

### `Darling\RoadyRoutingUtilities\classes\routers\Router`

A Router can accept a Request and return an appropriate Response for
that Request.

For example:

```php
$roadyRoutingUtilitiesTestingAPI = new \Darling\RoadyRoutingUtilities\tests\RoadyRoutingUtilitiesTest(
    'RouterExample'
);

$specificRequest = new \Darling\RoadyRoutingUtilities\classes\requests\Request(
    $roadyRoutingUtilitiesTestingAPI->randomUrlString()
);

$router = new \Darling\RoadyRoutingUtilities\classes\routers\Router(
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

```

Output:

```php
array(2) {
  ["Request: "]=>
  string(42) "http://foo.bar.baz:2343/some/path/bin.html"
  ["Number of Routes included in Response: "]=>
  int(155)
}
```

