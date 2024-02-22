<?php

namespace Darling\RoadyRoutingUtilities\tests;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \Darling\PHPTextTypes\classes\strings\Text as TextInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText as SafeTextInstance;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules as PathToDirectoryOfRoadyModulesInstance;
use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory as PathToExistingDirectoryInstance;
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
            $this->randomChars(),
            '',
            'http://',
            'http://foo.bar.baz:2343/',
            'http://foo.bar.baz:2343/some/path/bin.html',
            'http://foo.bar.baz:2343/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'http://foo.bar.baz:2343/some/path/bin.html?request=specific-request&q=a&b=c',
            'http://foo.bar:43/',
            'http://foo.bar:43/some/path/bin.html',
            'http://foo.bar:43/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'http://foo.bar:43/some/path/bin.html?request=specific-request&q=a&b=c',
            'http://foo:17/',
            'http://foo:17/some/path/bin.html',
            'http://foo:17/some/path/bin.html?request=specific-request&q=a&b=Kathooks%20Music',
            'http://foo:17/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'http://foo:17/some/path/bin.html?request=specific-request&q=a&b=c',
            'http://localhost',
            'http://localhost:80',
            'http://localhost:8080',
            'http://localhost:8888',
            'https://',
            'https://foo.bar.baz:2343/',
            'https://foo.bar.baz:2343/some/path/bin.html',
            'https://foo.bar.baz:2343/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'https://foo.bar.baz:2343/some/path/bin.html?request=specific-request&q=a&b=c',
            'https://foo.bar:43/',
            'https://foo.bar:43/some/path/bin.html',
            'https://foo.bar:43/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'https://foo.bar:43/some/path/bin.html?request=specific-request&q=a&b=c',
            'https://foo:17/',
            'https://foo:17/some/path/bin.html',
            'https://foo:17/some/path/bin.html?request=specific-request&q=a&b=c#frag',
            'https://foo:17/some/path/bin.html?request=specific-request&q=a&b=c',
            null,
        ];
        return $urlStrings[array_rand($urlStrings)];
    }

    /**
     * Return the path to the directory of test modules that will
     * be used to test the Router.
     *
     * @return PathToDirectoryOfRoadyModules
     *
     */
    public function pathToDirectoryOfRoadyTestModules(): PathToDirectoryOfRoadyModules
    {
        $testModuleDirectoryPathString  = __DIR__ . DIRECTORY_SEPARATOR . 'modules';
        $testModuleDirectoryPathStringParts = explode(DIRECTORY_SEPARATOR, $testModuleDirectoryPathString);
        $arrayOfSafeText = [];
        foreach($testModuleDirectoryPathStringParts as $part) {
            if(!empty($part)) {
                $arrayOfSafeText[] = new SafeTextInstance(new TextInstance($part));
            }
        }
        return new PathToDirectoryOfRoadyModulesInstance(
            new PathToExistingDirectoryInstance(
                new SafeTextCollectionInstance(...$arrayOfSafeText))
        );
    }

}
