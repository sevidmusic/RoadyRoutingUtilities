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

