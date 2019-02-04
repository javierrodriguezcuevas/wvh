<?php

namespace CodeChallenge\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Testwork\Tester\Result\TestResult;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DomCrawler\Crawler;
use PHPUnit\Framework\Assert;

/**
 * Class Adapted from: https://github.com/philsturgeon/build-apis-you-wont-hate/blob/master/chapter12/app/tests/behat/features/bootstrap/FeatureContext.php
 *
 * Original credits to Phil Sturgeon (https://twitter.com/philsturgeon)
 * and Ben Corlett (https://twitter.com/ben_corlett).
 *
 * A Behat context aimed at doing one awesome thing: interacting with APIs
 */
class ApiFeatureContext extends Assert implements Context
{
    /**
     * @var Response|null
     */
    private $response;

    /**
     * @var ConsoleOutput
     */
    private $output;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var PyStringNode
     */
    private $requestPayload;

    /**
     * @var array
     */
    private $responsePayload;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Client
     */
    private $client;

    /**
     * The current http resource
     */
    protected $resource;

    /**
     * The current http method
     */
    protected $httpMethod;

    public function __construct(EntityManagerInterface $em, string $baseUri)
    {
        $this->em = $em;
        $this->client = new Client([
            'base_uri' => $baseUri,
            'headers' => ['content-type' => 'application/json']
        ]);
    }

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function initializeResources(BeforeScenarioScope $scope)
    {
        $this->headers = [];
        $this->requestPayload = null;
        $this->responsePayload = [];
        $this->resource = null;
        $this->httpMethod = null;

        $this->cleanDatabase();
    }

    /**
     * @AfterScenario
     *
     * @param AfterScenarioScope $scope
     */
    public function cleanResources(AfterScenarioScope $scope)
    {
        //$this->cleanDatabase();
    }

    /**
     * @Given /^I set the "([^"]*)" header to be "([^"]*)"$/
     */
    public function iSetTheHeaderToBe($headerName, $value)
    {
        $this->headers[$headerName] = $value;
    }

    /**
     * @Given I have the payload:
     *
     * @param PyStringNode $requestPayload
     */
    public function iHaveThePayload(PyStringNode $requestPayload)
    {
        $this->requestPayload = $requestPayload;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     *
     * @throws \Exception
     */
    public function iRequest($httpMethod, $uri)
    {
        $this->httpMethod = $httpMethod;
        $this->resource = $uri;
        $method = strtolower($httpMethod);

        try {
            switch ($httpMethod) {
                case 'PUT':
                case 'POST':
                    $this->response = $this
                        ->client
                        ->$method($uri, [
                            'body' => $this->requestPayload
                        ]);
                    break;
                default:
                    $this->response = $this
                        ->client
                        ->$method($uri);
            }
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            // Sometimes the request will fail, at which point we have
            // no response at all. Let Guzzle give an error here, it's
            // pretty self-explanatory.
            if ($response === null) {
                throw $e;
            }
            $this->response = $response;
        }
    }

    /**
     * @Then /^the response status code should be (?P<code>\d+)$/
     *
     * @throws \Exception
     */
    public function theResponseStatusCodeShouldBe($statusCode)
    {
        $response = $this->getResponse();
        $contentType = $response->getHeaderLine('Content-Type');

        // looks for application/json or something like application/problem+json
        if (preg_match('#application\/(.)*\+?json#', $contentType)) {
            $bodyOutput = $response->getBody();
        } else {
            $bodyOutput = 'Output is "'.$contentType.'", which is not JSON and is therefore scary. Run the request manually.';
        }

        static::assertSame((int) $statusCode, (int) $this->getResponse()->getStatusCode(), $bodyOutput);
    }

    /**
     * @Given /^the "([^"]*)" header should exist$/
     *
     * @throws \Exception
     */
    public function theHeaderShouldExist($headerName)
    {
        $response = $this->getResponse();

        static::assertTrue($response->hasHeader($headerName));
    }

    /**
     * @Given /^the "([^"]*)" header should be "([^"]*)"$/
     *
     * @throws \Exception
     */
    public function theHeaderShouldBe($headerName, $expectedHeaderValue)
    {
        $response = $this->getResponse();

        static::assertEquals($expectedHeaderValue, (string) $response->getHeaderLine($headerName));
    }

    /**
     * @Then /^the "([^"]*)" property should exist$/
     *
     * @throws \Exception
     */
    public function thePropertyShouldExists($property)
    {
        $payload = $this->getResponsePayload();

        $message = sprintf(
            'Asserting the [%s] property exists in the scope: %s',
            $property,
            json_encode($payload)
        );

        $elements = explode('.', $property);
        $array = $payload;

        foreach ($elements as $element) {
            static::assertArrayHasKey($element, $array, $message);

            $array = $array[$element];
        }
    }

    /**
     * @Then /^the "([^"]*)" property should not exist$/
     *
     * @throws \Exception
     */
    public function thePropertyShouldNotExist($property)
    {
        $payload = $this->getResponsePayload();

        $message = sprintf(
            'Asserting the [%s] property does not exist in the scope: %s',
            $property,
            json_encode($payload)
        );

        static::assertArrayNotHasKey($property, $payload, $message);
    }

    /**
     * @Then /^the "([^"]*)" property should equal "([^"]*)"$/
     *
     * @throws \Exception
     */
    public function thePropertyShouldEqual($property, $expectedValue)
    {
        $payload = $this->getResponsePayload();

        $message = sprintf(
            "Asserting the [%s] property is equals [%s] in the scope: %s",
            $property,
            $expectedValue,
            json_encode($payload)
        );

        $elements = explode('.', $property);
        $actualValue = $payload;

        foreach ($elements as $element) {
            static::assertArrayHasKey($element, $actualValue, $message);

            $actualValue = $actualValue[$element];
        }

        if (in_array($expectedValue, ["true", "false"])) {
            $expectedValue = filter_var($expectedValue, FILTER_VALIDATE_BOOLEAN);
        }

        static::assertEquals($expectedValue, $actualValue, $message);
    }

    /**
     * @Then /^the "([^"]*)" property should be an array$/
     *
     * @throws \Exception
     */
    public function thePropertyShouldBeAnArray($property)
    {
        $payload = $this->getResponsePayload();

        $message = sprintf(
            "Asserting the [%s] property is an array in the scope: %s",
            $property,
            json_encode($payload)
        );

        $elements = explode('.', $property);
        $array = $payload;

        foreach ($elements as $element) {
            static::assertIsArray($array, $message);
            static::assertArrayHasKey($element, $array, $message);

            $array = $array[$element];
        }
    }

    /**
     * @Then /^the "([^"]*)" property should contain "([^"]*)"$/
     *
     * @throws \Exception
     */
    public function thePropertyShouldContain($property, $expectedValue)
    {
        $payload = $this->getResponsePayload();

        $message = sprintf(
            "Asserting the [%s] property contains [%s] in the scope: %s",
            $property,
            $expectedValue,
            json_encode($payload)
        );


        $elements = explode('.', $property);
        $actualValue = $payload;

        foreach ($elements as $element) {
            $actualValue = $actualValue[$element];
        }

        static::assertContains($actualValue, $expectedValue, $message);
    }

    /**
     * @Then /^the following properties should exist:$/
     *
     * @throws \Exception
     */
    public function theFollowingPropertiesShouldExist(PyStringNode $propertiesString)
    {
        foreach (explode("\n", (string) $propertiesString) as $property) {
            $this->thePropertyShouldExists($property);
        }
    }

    /**
     * Asserts the the href of the given link name equals this value
     *
     * Since we're using HAL, this would look for something like:
     *      "_links.self.href": "/api/companies/CapsuleCorp"
     *
     * @Given /^the HAL link "([^"]*)" should exist and its value should be "([^"]*)"$/
     *
     * @throws \Exception
     */
    public function theLinkShouldExistAndItsValueShouldBe($linkName, $url)
    {
        $this->thePropertyShouldEqual(
            sprintf('_links.%s.href', $linkName),
            $url
        );
    }

    /**
     * @Given /^the embedded "([^"]*)" should have a "([^"]*)" property equal to "([^"]*)"$/
     *
     * @throws \Exception
     */
    public function theEmbeddedShouldHaveAPropertyEqualTo($embeddedName, $property, $value)
    {
        $this->thePropertyShouldEqual(
            sprintf('_embedded.%s.%s', $embeddedName, $property),
            $value
        );
    }

    /**
     * Checks the response exists and returns it.
     *
     * @return Response
     *
     * @throws \Exception
     */
    protected function getResponse()
    {
        if (!$this->response) {
            throw new \Exception("You must first make a request to check a response.");
        }

        return $this->response;
    }

    /**
     * Get response content
     *
     * @param bool $reload
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function getResponsePayload(bool $reload = false)
    {
        if (!$this->responsePayload || $reload) {
            $json = json_decode($this->getResponse()->getBody()->__toString(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $message = 'Failed to decode JSON body ';

                switch (json_last_error()) {
                    case JSON_ERROR_DEPTH:
                        $message .= '(Maximum stack depth exceeded).';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $message .= '(Underflow or the modes mismatch).';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $message .= '(Unexpected control character found).';
                        break;
                    case JSON_ERROR_SYNTAX:
                        $message .= '(Syntax error, malformed JSON): '."\n\n".$this->getResponse()->getBody()->getContents();
                        break;
                    case JSON_ERROR_UTF8:
                        $message .= '(Malformed UTF-8 characters, possibly incorrectly encoded).';
                        break;
                    default:
                        $message .= '(Unknown error).';
                        break;
                }

                throw new \Exception($message);
            }

            $this->responsePayload = $json;
        }

        return $this->responsePayload;
    }

    public function printDebug($string)
    {
        $this->getOutput()->writeln($string);
    }

    private function cleanDatabase()
    {
        $purger = new ORMPurger($this->em);

        $purger->purge();
    }

    /**
     * @return ConsoleOutput
     */
    private function getOutput()
    {
        if ($this->output === null)  {
            $this->output = new ConsoleOutput();
        }

        return $this->output;
    }

    /**
     * @AfterScenario
     *
     * @throws \Exception
     */
    public function printLastResponseOnError(AfterScenarioScope $scenarioScope)
    {
        if ($scenarioScope->getTestResult()->getResultCode() == TestResult::FAILED) {
            if ($this->response) {
                $body = $this->getResponse()->getBody()->getContents();

                // print some debug details
                $this->printDebug('');
                $this->printDebug('<error>Failure!</error> when making the following request:');
                $this->printDebug(sprintf('<comment>%s</comment>: <info>%s</info>', $this->httpMethod, $this->resource)."\n");

                $contentType = $this->response->getHeaderLine('Content-Type');

                if (preg_match('#application\/(.)*\+?json#', $contentType)) {
                    $data = json_decode($body);
                    if ($data === null) {
                        // invalid JSON!
                        $this->printDebug($body);
                    } else {
                        // valid JSON, print it pretty
                        $this->printDebug(json_encode($data, JSON_PRETTY_PRINT));
                    }
                } else {
                    // the response is HTML - see if we should print all of it or some of it
                    $isValidHtml = strpos($body, '</body>') !== false;

                    if ($isValidHtml) {
                        $this->printDebug('<error>Failure!</error> Below is a summary of the HTML response from the server.');

                        // finds the h1 and h2 tags and prints them only
                        $crawler = new Crawler($body);
                        foreach ($crawler->filter('h1, h2')->extract(array('_text')) as $header) {
                            $this->printDebug(sprintf('        '.$header));
                        }
                    } else {
                        $this->printDebug($body);
                    }
                }
            }
        }
    }
}
