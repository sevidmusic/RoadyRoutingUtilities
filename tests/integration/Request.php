<?php

require(
    str_replace(
        'tests' . DIRECTORY_SEPARATOR . 'integration',
        'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
        __DIR__
    )
);


/**
 * Define a Request that represents the current Request to the
 * server.
 */

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


