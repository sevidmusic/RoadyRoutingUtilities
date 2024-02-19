<?php

namespace Darling\RoadyRoutingUtilities\tests\interfaces\requests;

use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText as SafeTextInstance;
use \Darling\PHPTextTypes\classes\strings\Text as TextInstance;
use \Darling\PHPTextTypes\interfaces\collections\SafeTextCollection;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\PHPWebPaths\classes\paths\Domain as DomainInstance;
use \Darling\PHPWebPaths\classes\paths\Url as UrlInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\Authority as AuthorityInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\DomainName as DomainNameInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\Fragment as FragmentInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\Host as HostInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\Path as PathInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\Port as PortInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\Query as QueryInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\SubDomainName as SubDomainNameInstance;
use \Darling\PHPWebPaths\classes\paths\parts\url\TopLevelDomainName as TopLevelDomainNameInstance;
use \Darling\PHPWebPaths\enumerations\paths\parts\url\Scheme;
use \Darling\PHPWebPaths\interfaces\paths\Url;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;
use \PHPUnit\Framework\Attributes\CoversClass;

/**
 * The RequestTestTrait defines common tests for implementations of
 * the Request interface.
 */
#[CoversClass(Request::class)]
trait RequestTestTrait
{

    /**
     * @var non-empty-string $defaultHost Default Host used if host
     *                                    cannot be determined from
     *                                    $testUrlString.
     */
    private string $defaultHost = 'localhost';

    /**
     * @var non-empty-string $requestParameterName Name of the url
     *                                             query parameter
     *                                             used to determine
     *                                             the Name that is
     *                                             expected to be
     *                                             assigned to the
     *                                             Request.
     */
    private string $requestParameterName = 'request';

    /**
     * @var non-empty-string $httpsOnValue The value that will be
     *                                     assigned to
     *                                     $_SERVER['HTTPS'] if
     *                                     `https` is enabled.
     */
    private string $httpsOnValue = 'on';

    /**
     * @var non-empty-string $domainSeparator Character used to
     *                                        separate the sub-domain,
     *                                        domain, and top-level
     *                                        domain of a url.
     */
    private string $domainSeparator = '.';

    /**
     * @var non-empty-string $queryParameterName Key of the `query`
     *                                           value in the
     *                                           array returned by
     *                                           parse_url().
     */
    private string $queryParameterName = 'query';

    /**
     * @var non-empty-string $fragmentParameterName Key of the
     *                                              `fragment` value
     *                                              in the array
     *                                              returned
     *                                              by parse_url().
     */
    private string $fragmentParameterName = 'fragment';

    /**
     * @var non-empty-string $schemeParameterName Key of the `scheme`
     *                                            value in the array
     *                                            returned
     *                                            by parse_url().
     */
    private string $schemeParameterName = 'scheme';

    /**
     * @var string|null $testUrlString The url string to use for
     *                                 testing. Defaults to null.
     */
    private string|null $testUrlString = null;

    /**
     * @var non-empty-string $defaultRequestName Default Request Name
     *                                           used if name cannot
     *                                           be determined from
     *                                           $testUrlString.
     */
    private string $defaultRequestName = 'homepage';

    /**
     * @var Request $request An instance of a Request implementation
     *                       to test.
     */
    protected Request $request;

    /**
     * Set up an instance of a Request implementation to test.
     *
     * This method must set the Request implementation instance
     * to be tested via the setRequestTestInstance() method.
     *
     * This method must also set the test url string
     * to use for testing via the setTestUrlString() method.
     *
     * This method may also be used to perform any additional setup
     * required by the implementation being tested.
     *
     * @return void
     *
     * @example
     *
     * ```
     * public function setUp(): void
     * {
     *     $urlString = $this->randomUrlString();
     *     $this->setTestUrlString($urlString);
     *     $this->setRequestTestInstance(
     *         new Request($urlString)
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the Request implementation instance to test.
     *
     * @return Request
     *
     */
    protected function requestTestInstance(): Request
    {
        return $this->request;
    }

    /**
     * Set the Request implementation instance to test.
     *
     * @param Request $requestTestInstance An instance of an
     *                                     implementation of the
     *                                     Request interface to test.
     *
     * @return void
     *
     */
    protected function setRequestTestInstance(
        Request $requestTestInstance
    ): void
    {
        $this->request = $requestTestInstance;
    }

    /**
     * Return the Name that is expected to be returned by the
     * name() method of the Request implementation being tested.
     *
     * The value of this Name will be determined based on the
     * current value of this Trait's $testUrlString property.
     *
     * If a Name cannot be determined from this Trait's $testUrlString
     * property's value then this method will attempt to determine the
     * Name from the value of $_POST[$this->requestParameterName] or
     * $_GET[$this->requestParameterName].
     *
     * Finally, if a Name cannot be determined from the $testUrlString,
     * $_POST, or $_GET, then a Name whose string value is assigned
     * the value of this Trait's $defaultRequestName property
     * will be returned.
     *
     * @return Name
     *
     */
    protected function expectedName(): Name
    {
        if(isset($this->testUrlString) && !empty($this->testUrlString)) {
            $urlParts = parse_url($this->testUrlString);
            if(
                isset($urlParts[$this->queryParameterName])
                &&
                is_string($urlParts[$this->queryParameterName])
            ) {
                $query = [];
                parse_str(
                    $urlParts[$this->queryParameterName],
                    $query
                );
                if(
                    isset($query[$this->requestParameterName])
                    &&
                    is_string($query[$this->requestParameterName])
                    &&
                    !empty($query[$this->requestParameterName])
                ) {
                    return new NameInstance(
                        new TextInstance(
                            $query[$this->requestParameterName]
                        )
                    );
                }
            }
        }
        if(
            isset($_POST[$this->requestParameterName])
            &&
            is_string($_POST[$this->requestParameterName])
            &&
            !empty($_POST[$this->requestParameterName])
        ) {
            return new NameInstance(
                new TextInstance($_POST[$this->requestParameterName])
            );
        }
        if(
            isset($_GET[$this->requestParameterName])
            &&
            is_string($_GET[$this->requestParameterName])
            &&
            !empty($_GET[$this->requestParameterName])
        ) {
            return new NameInstance(
                new TextInstance($_GET[$this->requestParameterName])
            );
        }
        return new NameInstance(new TextInstance($this->defaultRequestName));
    }

    /**
     * Set the value of the url string that will be used to test
     * the Request implementation being tested.
     *
     * @return void
     *
     */
    protected function setTestUrlString(string|null $urlString): void
    {
        $this->testUrlString = $urlString;
    }

    /**
     * Return the value of the url string that will be used to test
     * the Request implementation being tested.
     *
     * @return string|null
     *
     */
    private function testUrlString(): string|null
    {
        return $this->testUrlString;
    }

    /**
     * Determine the Url that is expected to be returned by the
     * Request implementation being tested's url() method.
     *
     * The Url will be determined based on the current value of
     * this Trait's $testUrlString property.
     *
     * @return Url
     *
     */
    public function expectedUrl(): Url
    {
        $currentRequestsUrlParts = parse_url(
            (
                isset($this->testUrlString) && !empty($this->testUrlString)
                ? $this->testUrlString
                : $this->determineCurrentRequestUrlString()
            )
        );
        if(is_array($currentRequestsUrlParts)) {
            $domains = explode(
                $this->domainSeparator,
                $currentRequestsUrlParts['host'] ?? $this->defaultHost
            );
            $port = intval($currentRequestsUrlParts['port'] ?? null);
            $path = ($currentRequestsUrlParts['path'] ?? null);
            $query = (
                $currentRequestsUrlParts[$this->queryParameterName]
                ??
                null
            );
            $fragment = (
                $currentRequestsUrlParts[$this->fragmentParameterName]
                ??
                null
            );
            $scheme = match(
                $currentRequestsUrlParts[$this->schemeParameterName]
                ??
                null
            ) {
                Scheme::HTTPS->value => Scheme::HTTPS,
                default => Scheme::HTTP,
            };

            return match(count($domains)) {
                1 => $this->newUrl(
                    domainName: $domains[0],
                    fragment: (is_string($fragment) ? $fragment : null),
                    path: $path,
                    port: $port,
                    query: (is_string($query) ? $query : null),
                    scheme: $scheme,
                ),
                2 => $this->newUrl(
                    domainName: $domains[1],
                    fragment: (is_string($fragment) ? $fragment : null),
                    path: $path,
                    port: $port,
                    query: (is_string($query) ? $query : null),
                    scheme: $scheme,
                    subDomainName: $domains[0],
                ),
                3 => $this->newUrl(
                    domainName: $domains[1],
                    fragment: (is_string($fragment) ? $fragment : null),
                    path: $path,
                    port: $port,
                    query: (is_string($query) ? $query : null),
                    scheme: $scheme,
                    subDomainName: $domains[0],
                    topLevelDomainName: $domains[2],
                ),
                default => $this->newUrl(
                    domainName: $this->defaultHost,
                    fragment: (is_string($fragment) ? $fragment : null),
                    path: $path,
                    port: $port,
                    query: (is_string($query) ? $query : null),
                    scheme: $scheme,
                ),
            };
        }
        return $this->defaultUrl();
    }

    /**
     * Return a new Url instance based on the specified parameters.
     *
     * @return Url
     *
     */
    private function newUrl(
        string $domainName,
        string $subDomainName = null,
        string $topLevelDomainName = null,
        int $port = null,
        string $path = null,
        string $query = null,
        string $fragment = null,
        Scheme $scheme = null,
    ): Url
    {
        return new UrlInstance(
            domain: new DomainInstance(
                scheme: (isset($scheme) ? $scheme : Scheme::HTTP),
                authority: new AuthorityInstance(
                    host: new HostInstance(
                        subDomainName: (
                            isset($subDomainName)
                            ? new SubDomainNameInstance(
                                new NameInstance(
                                    new TextInstance($subDomainName)
                                )
                            )
                            : null
                        ),
                        domainName: new DomainNameInstance(
                            new NameInstance(
                                new TextInstance($domainName)
                            )
                        ),
                        topLevelDomainName: (
                            isset($topLevelDomainName)
                            ? new TopLevelDomainNameInstance(
                                new NameInstance(
                                    new TextInstance(
                                        $topLevelDomainName
                                    )
                                )
                            )
                            : null
                        ),
                    ),
                    port: (
                        isset($port)
                        ? new PortInstance($port)
                        : null
                    ),
                ),
            ),
            path: (
                isset($path)
                ? new PathInstance(
                    $this->deriveSafeTextCollectionFromPathString(
                        $path
                    )
                )
                : null
            ),
            query: (
                isset($query)
                ? new QueryInstance(new TextInstance($query))
                : null
            ),
            fragment: (
                isset($fragment)
                ? new FragmentInstance(new TextInstance($fragment))
                : null
            ),
        );
    }

    /**
     * Determine the an approprite url string for the current Request
     * based on the values set in the $_SERVER array.
     *
     * This method is called when the current $testUrlString is
     * set to null.
     *
     * @return string
     *
     */
    private function determineCurrentRequestUrlString(): string
    {
        $scheme = (
            isset($_SERVER['HTTPS'])
            &&
            $_SERVER['HTTPS'] === $this->httpsOnValue
            ? Scheme::HTTPS
            : Scheme::HTTP
        );
        $host = ($_SERVER['HTTP_HOST'] ?? $this->defaultHost);
        $uri = ($_SERVER['REQUEST_URI'] ?? '');
        return $scheme->value . '://' . $host . $uri;
    }

    private function deriveSafeTextCollectionFromPathString(
        string $path
    ): SafeTextCollection
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $path);
        $safeText = [];
        foreach ($pathParts as $pathPart) {
            if (!empty($pathPart)) {
                $safeText[] = new SafeTextInstance(
                    new TextInstance($pathPart)
                );
            }
        }
        return new SafeTextCollectionInstance(...$safeText);
    }

    /**
     * Return the default Url instance that will be returned by
     * the expectedUrl() method if the actual url cannot be
     * determined.
     *
     * @return Url
     *
     */
    private function defaultUrl(): Url
    {
        return $this->newUrl(domainName: $this->defaultHost);
    }

    /**
     * Test that the name() method returns the expected Name.
     *
     * @return void
     *
     */
    public function test_name_returns_expected_name(): void
    {
        $this->assertEquals(
            $this->expectedName(),
            $this->requestTestInstance()->name(),
            $this->testFailedMessage(
                $this->requestTestInstance(),
                'name',
                'name() must return the expected Name: ' . $this->expectedName()->__toString(),
            )
        );
    }

    /**
     * Test that the url() method returns the expected Url.
     *
     * @return void
     *
     */
    public function test_url_returns_expected_url(): void
    {
        $this->assertEquals(
            $this->expectedUrl(),
            $this->requestTestInstance()->url(),
            $this->testFailedMessage(
                $this->requestTestInstance(),
                'name',
                'name() must return the expected Name: ' . $this->expectedName()->__toString(),
            )
        );
    }

    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;
    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;

}

