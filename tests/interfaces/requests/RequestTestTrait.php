<?php

namespace Darling\RoadyRoutingUtilities\tests\interfaces\requests;

use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\Text as TextInstance;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;

/**
 * The RequestTestTrait defines common tests for implementations of
 * the Request interface.
 *
 * @see Request
 *
 */
trait RequestTestTrait
{

    /**
     * Default Host used if host cannot be determined from
     * $testUrlString.
     */
    private string $defaultHost = 'localhost';

    /**
     * Name of the url query parameter used to determine the Name
     * that is expected to be assigned to the Request.
     */
    private string $requestParameterName = 'request';

    /**
     * The value that will be assigned to $_SERVER['HTTPS'] if
     * `https` is enabled.
     */
    private string $httpsOnValue = 'on';

    /**
     * Character used to separate the sub-domain, domain, and top-level
     * domain of a url.
     */
    private string $domainSeparator = '.';

    /**
     * Key of the `query` value in the array returned by parse_url().
     */
    private string $queryParameterName = 'query';

    /**
     * Name of the url query parameter used to determine the Fragment
     * that is expected to be assigned to the Request's Url.
     */
    private string $fragmentParameterName = 'fragment';

    /**
     * Name of the url query parameter used to determine the Scheme
     * that is expected to be assigned to the Request's Url.
     */
    private string $schemeParameterName = 'scheme';

    private string|null $testUrlString = null;

    /**
     * Default Request Name used if host cannot be determined from
     * $testUrlString.
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
     * This method may also be used to perform any additional setup
     * required by the implementation being tested.
     *
     * @return void
     *
     * @example
     *
     * ```
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
     * Test that the name() method returns the expected Name.
     *
     * @return void
     *
     * @covers Request->name()
     *
     */
    public function test_name_returns_expected_named(): void
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

    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;
    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;

}

