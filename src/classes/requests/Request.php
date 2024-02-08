<?php

namespace Darling\RoadyRoutingUtilities\classes\requests;

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
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request as RequestInterface;

class Request implements RequestInterface
{

    private const DEFAULT_HOST = 'localhost';
    private const REQUEST_PARAMETER_NAME = 'request';
    private const HTTPS_ON_VALUE = 'on';
    private const DOMAIN_SEPARATOR = '.';
    private const QUERY_PARAMETER_NAME = 'query';
    private const FRAGMENT_PARAMETER_NAME = 'fragment';
    private const SCHEME_PARAMETER_NAME = 'scheme';
    private const DEFAULT_REQUEST_NAME = 'homepage';

    public function __construct(
        private string|null $urlString = null
    ) {}

    public function name(): Name
    {
        if(isset($this->urlString) && !empty($this->urlString)) {
            $urlParts = parse_url($this->urlString);
            if(isset($urlParts[self::QUERY_PARAMETER_NAME])) {
                $query = [];
                parse_str(
                    $urlParts[self::QUERY_PARAMETER_NAME],
                    $query
                );
                if(
                    isset($query[self::REQUEST_PARAMETER_NAME])
                    &&
                    is_string($query[self::REQUEST_PARAMETER_NAME])
                    &&
                    !empty($query[self::REQUEST_PARAMETER_NAME])
                ) {
                    return new NameInstance(
                        new TextInstance(
                            $query[self::REQUEST_PARAMETER_NAME]
                        )
                    );
                }
            }
        }
        if(
            isset($_POST[self::REQUEST_PARAMETER_NAME])
            &&
            is_string($_POST[self::REQUEST_PARAMETER_NAME])
            &&
            !empty($_POST[self::REQUEST_PARAMETER_NAME])
        ) {
            return new NameInstance(
                new TextInstance($_POST[self::REQUEST_PARAMETER_NAME])
            );
        }
        if(
            isset($_GET[self::REQUEST_PARAMETER_NAME])
            &&
            is_string($_GET[self::REQUEST_PARAMETER_NAME])
            &&
            !empty($_GET[self::REQUEST_PARAMETER_NAME])
        ) {
            return new NameInstance(
                new TextInstance($_GET[self::REQUEST_PARAMETER_NAME])
            );
        }
        return new NameInstance(new TextInstance(self::DEFAULT_REQUEST_NAME));
    }

    public function url(): Url
    {
        $currentRequestsUrlParts = parse_url(
            (
                isset($this->urlString) && !empty($this->urlString)
                ? $this->urlString
                : $this->determineCurrentRequestUrlString()
            )
        );
        if(is_array($currentRequestsUrlParts)) {
            $domains = explode(
                self::DOMAIN_SEPARATOR,
                $currentRequestsUrlParts['host'] ?? self::DEFAULT_HOST
            );
            $port = intval($currentRequestsUrlParts['port'] ?? null);
            $path = ($currentRequestsUrlParts['path'] ?? null);
            $query = (
                $currentRequestsUrlParts[self::QUERY_PARAMETER_NAME]
                ??
                null
            );
            $fragment = (
                $currentRequestsUrlParts[self::FRAGMENT_PARAMETER_NAME]
                ??
                null
            );
            $scheme = match(
                $currentRequestsUrlParts[self::SCHEME_PARAMETER_NAME]
                ??
                null
            ) {
                Scheme::HTTPS->value => Scheme::HTTPS,
                default => Scheme::HTTP,
            };

            return match(count($domains)) {
                1 => $this->newUrl(
                    domainName: $domains[0],
                    fragment: $fragment,
                    path: $path,
                    port: $port,
                    query: $query,
                    scheme: $scheme,
                ),
                2 => $this->newUrl(
                    domainName: $domains[1],
                    fragment: $fragment,
                    path: $path,
                    port: $port,
                    query: $query,
                    scheme: $scheme,
                    subDomainName: $domains[0],
                ),
                3 => $this->newUrl(
                    domainName: $domains[1],
                    fragment: $fragment,
                    path: $path,
                    port: $port,
                    query: $query,
                    scheme: $scheme,
                    subDomainName: $domains[0],
                    topLevelDomainName: $domains[2],
                ),
                default => $this->newUrl(
                    domainName: self::DEFAULT_HOST,
                    fragment: $fragment,
                    path: $path,
                    port: $port,
                    query: $query,
                    scheme: $scheme,
                ),
            };
        }
        return $this->defaultUrl();
    }


    /**
     * Return a new Url instance based on the specified parameters.
     *
     * @param string $domainName The domain name to assign to the Url.
     *
     * @param string $subDomainName The sub-domain-name to assign to the Url.
     *
     * @param string $topLevelDomainName The top-level-domain-name to assign to the Url.
     *
     * @param int $port The port to assign to the Url.
     *
     * @param string $path The path to assign to the Url.
     *
     * @param string $query The query to assign to the Url.
     *
     * @param string $fragment The fragment to assign to the Url.
     *
     * @param Scheme $scheme The Scheme to assign to the Url.
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
     * Return a SafeTextCollection constructed using the parts of
     * the specified $path.
     *
     * @return SafeTextCollection
     *
     */
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
     * the url() method if the actual url cannot be determined.
     *
     * @return Url
     *
     */
    private function defaultUrl(): Url
    {
        return $this->newUrl(domainName: self::DEFAULT_HOST);
    }

    /**
     * Determine an appropriate url string for the current Request
     * based on the values set in the $_SERVER array.
     *
     * @return string
     *
     */
    private function determineCurrentRequestUrlString(): string
    {
        $scheme = (
            isset($_SERVER['HTTPS'])
            &&
            $_SERVER['HTTPS'] === self::HTTPS_ON_VALUE
            ? Scheme::HTTPS
            : Scheme::HTTP
        );
        $host = ($_SERVER['HTTP_HOST'] ?? self::DEFAULT_HOST);
        $uri = ($_SERVER['REQUEST_URI'] ?? '');
        return $scheme->value . '://' . $host . $uri;
    }

}

