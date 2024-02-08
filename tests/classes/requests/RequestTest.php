<?php

namespace Darling\RoadyRoutingUtilities\tests\classes\requests;

use \Darling\RoadyRoutingUtilities\classes\requests\Request;
use \Darling\RoadyRoutingUtilities\tests\RoadyRoutingUtilitiesTest;
use \Darling\RoadyRoutingUtilities\tests\interfaces\requests\RequestTestTrait;

class RequestTest extends RoadyRoutingUtilitiesTest
{

    /**
     * The RequestTestTrait defines common tests for implementations
     * of the Darling\RoadyRoutingUtilities\interfaces\requests\Request
     * interface.
     *
     * @see RequestTestTrait
     *
     */
    use RequestTestTrait;

    public function setUp(): void
    {
       $urlStrings = [
            'https://foo.bar.baz:2343/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'https://foo.bar:43/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'https://foo:17/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'http://foo.bar.baz:2343/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'http://foo.bar:43/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'http://foo:17/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'https://foo.bar.baz:2343/some/path/bin.html?request=specific-request&q=a&b=c',
            'https://foo.bar:43/some/path/bin.html?request=specific-request&q=a&b=c',
            'https://foo:17/some/path/bin.html?request=specific-request&q=a&b=c',
            'http://foo.bar.baz:2343/some/path/bin.html?request=specific-request&q=a&b=c',
            'http://foo.bar:43/some/path/bin.html?request=specific-request&q=a&b=c',
            'http://foo:17/some/path/bin.html?request=specific-request&q=a&b=c',
            'http://foo:17/some/path/bin.html?request=specific-request&q=a&b=Kathooks%20Music',
            'https://foo.bar.baz:2343/some/path/bin.html',
            'https://foo.bar:43/some/path/bin.html',
            'https://foo:17/some/path/bin.html',
            'http://foo.bar.baz:2343/some/path/bin.html',
            'http://foo.bar:43/some/path/bin.html',
            'http://foo:17/some/path/bin.html',
            'https://foo.bar.baz:2343/',
            'https://foo.bar:43/',
            'https://foo:17/',
            'http://foo.bar.baz:2343/',
            'http://foo.bar:43/',
            'http://foo:17/',
            'https://',
            'http://',
            '',
             $this->randomChars(),
            null,
        ];
        $urlString = $urlStrings[array_rand($urlStrings)];
        $this->setTestUrlString($urlString);
        $this->setRequestTestInstance(
            new Request($urlString)
        );
    }

}

