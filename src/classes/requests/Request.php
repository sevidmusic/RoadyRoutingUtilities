<?php

namespace Darling\RoadyRoutingUtilities\classes\requests;

use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\Text as TextInstance;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request as RequestInterface;

class Request implements RequestInterface
{

#    private const DEFAULT_HOST = 'localhost';
    private const REQUEST_PARAMETER_NAME = 'request';
#    private const HTTPS_ON_VALUE = 'on';
#    private const DOMAIN_SEPARATOR = '.';
    private const QUERY_PARAMETER_NAME = 'query';
#    private const FRAGMENT_PARAMETER_NAME = 'fragment';
#    private const SCHEME_PARAMETER_NAME = 'scheme';
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

}

