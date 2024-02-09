<?php

namespace Darling\RoadyRoutingUtilities\tests;

use \Darling\PHPUnitTestUtilities\traits\PHPUnitConfigurationTests;
use \Darling\PHPUnitTestUtilities\traits\PHPUnitRandomValues;
use \Darling\PHPUnitTestUtilities\traits\PHPUnitTestMessages;
use \PHPUnit\Framework\Attributes\CoversNothing;
use \PHPUnit\Framework\TestCase;

/**
 * Defines common methods that may be useful to all
 * RoadyRoutingUtilities test classes.
 *
 * All RoadyRoutingUtilities test classes must extend from this class.
 *
 */
#[CoversNothing]
class RoadyRoutingUtilitiesTest extends TestCase
{
    use PHPUnitConfigurationTests;
    use PHPUnitTestMessages;
    use PHPUnitRandomValues;


    /**
     * Return a random url string or null.
     *
     * @return string|null
     *
     */
    public function randomUrlString(): string|null
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
        return $urlStrings[array_rand($urlStrings)];
    }

}
