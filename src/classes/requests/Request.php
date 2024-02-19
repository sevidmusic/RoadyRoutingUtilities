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

    /**
     * @const non-empty-string SERVER_REQUEST_URI_KEY The key of the
     *                                                value of the
     *                                                current request's
     *                                                uri in the
     *                                                $_SERVER array.
     */
    private const SERVER_REQUEST_URI_KEY = 'REQUEST_URI';

    /**
     * @const non-empty-string SERVER_HTTP_HOST_KEY The key of the
     *                                              value of the
     *                                              current request's
     *                                              host in the
     *                                              $_SERVER array.
     */
    private const SERVER_HTTP_HOST_KEY = 'HTTP_HOST';

    /**
     * @const non-empty-string SERVER_HTTPS_KEY The key of the
     *                                          value of the
     *                                          current request's
     *                                          https value in the
     *                                          $_SERVER array.
     */
    private const SERVER_HTTPS_KEY = 'HTTPS';

    /**
     * @const non-empty-string PARSE_URL_PATH_KEY The key of the
     *                                            `path` value in
     *                                            the array returned
     *                                            by parse_url().
     */
    private const PARSE_URL_PATH_KEY = 'path';

    /**
     * @const non-empty-string PARSE_URL_PORT_KEY The key of the
     *                                            `port` value in
     *                                            the array returned
     *                                            by parse_url().
     */
    private const PARSE_URL_PORT_KEY = 'port';

    /**
     * @const non-empty-string DEFAULT_HOST Default Host used if host
     *                                      cannot be determined from
     *                                      $this->urlString.
     */
    private const DEFAULT_HOST = 'localhost';

    /**
     * @const non-empty-string PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY
     *                                                The key of the
     *                                                query parameter
     *                                                value used to
     *                                                determine the
     *                                                Name of the
     *                                                current Request.
     *
     *                                                This will either
     *                                                be the key of
     *                                                the query
     *                                                parameter value
     *                                                in the array
     *                                                returned by
     *                                                parse_url(),
     *                                                the $_POST
     *                                                array, or the
     *                                                $_GET array.
     */
    private const PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY = 'request';

    /**
     * @const non-empty-string HTTPS_ON_VALUE The value that will be
     *                                        assigned to
     *                                        $_SERVER['HTTPS'] if
     *                                        `https` is enabled.
     */
    private const HTTPS_ON_VALUE = 'on';

    /**
     * @const non-empty-string DOMAIN_SEPARATOR The character
     *                                          used to separate
     *                                          the sub-domain,
     *                                          domain, and top-level
     *                                          domain of a url.
     */
    private const DOMAIN_SEPARATOR = '.';

    /**
     * @const non-empty-string PARSE_URL_QUERY_KEY Key of the `query`
     *                                             value in the
     *                                             array returned by
     *                                             parse_url().
     */
    private const PARSE_URL_QUERY_KEY = 'query';

    /**
     * @const non-empty-string PARSE_URL_FRAGMENT_KEY Key of the
     *                                                `fragment` value
     *                                                in the array
     *                                                returned
     *                                                by parse_url().
     */
    private const PARSE_URL_FRAGMENT_KEY = 'fragment';

    /**
     * @const non-empty-string PARSE_URL_SCHEME_KEY Key of the
     *                                              `scheme` value
     *                                              in the array
     *                                              returned
     *                                              by parse_url().
     */
    private const PARSE_URL_SCHEME_KEY = 'scheme';

    /**
     * @var non-empty-string DEFAULT_REQUEST_NAME
     *                                  Default Request Name used if
     *                                  name cannot be determined from
     *                                  parse_url($this->urlString),
     *                                  $_POST, or $_GET.
     */
    private const DEFAULT_REQUEST_NAME = 'homepage';

    /**
     * Instantiate a new Request instance.
     *
     * If a $urlString is specified, the Request will represent the
     * request implied by the specified $urlString.
     *
     * If a $urlString is not specified, the Request will represent
     * the current request to the server based on the state of the
     * $_SERVER, $_POST, and $_GET arrays.
     *
     * @param string|null $urlString A string that defines the url to
     *                               construct the Request from.
     *                               If a $urlString is not specified
     *                               then the Request will represent
     *                               the current request to the server.
     *
     */
    public function __construct(
        private string|null $urlString = null
    ) {}

    public function name(): Name
    {
        if(isset($this->urlString) && !empty($this->urlString)) {
            $urlParts = parse_url($this->urlString);
            if(isset($urlParts[self::PARSE_URL_QUERY_KEY])) {
                $query = [];
                parse_str(
                    $urlParts[self::PARSE_URL_QUERY_KEY],
                    $query
                );
                if(
                    isset($query[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
                    &&
                    is_string($query[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
                    &&
                    !empty($query[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
                ) {
                    return new NameInstance(
                        new TextInstance(
                            $query[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY]
                        )
                    );
                }
            }
        }
        if(
            isset($_POST[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
            &&
            is_string($_POST[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
            &&
            !empty($_POST[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
        ) {
            return new NameInstance(
                new TextInstance($_POST[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
            );
        }
        if(
            isset($_GET[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
            &&
            is_string($_GET[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
            &&
            !empty($_GET[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
        ) {
            return new NameInstance(
                new TextInstance($_GET[self::PARSE_URL_GET_OR_POST_REQUEST_QUERY_KEY])
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
            $port = intval($currentRequestsUrlParts[self::PARSE_URL_PORT_KEY] ?? null);
            $path = ($currentRequestsUrlParts[self::PARSE_URL_PATH_KEY] ?? null);
            $query = (
                $currentRequestsUrlParts[self::PARSE_URL_QUERY_KEY]
                ??
                null
            );
            $fragment = (
                $currentRequestsUrlParts[self::PARSE_URL_FRAGMENT_KEY]
                ??
                null
            );
            $scheme = match(
                $currentRequestsUrlParts[self::PARSE_URL_SCHEME_KEY]
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
     * @param string $path The path to construct the
     *                     SafeTextCollection from.
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
     * the url() method if an appropriate url cannot be determined
     * based on the current state of $this->urlString, the $_SERVER
     * array, the $_POST array, or the $_GET array.
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
            isset($_SERVER[self::SERVER_HTTPS_KEY])
            &&
            $_SERVER[self::SERVER_HTTPS_KEY] === self::HTTPS_ON_VALUE
            ? Scheme::HTTPS
            : Scheme::HTTP
        );
        $host = ($_SERVER[self::SERVER_HTTP_HOST_KEY] ?? self::DEFAULT_HOST);
        $uri = ($_SERVER[self::SERVER_REQUEST_URI_KEY] ?? '');
        return $scheme->value . '://' . $host . $uri;
    }

}

