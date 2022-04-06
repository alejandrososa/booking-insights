<?php

declare(strict_types=1);

namespace Kata\Tests\Shared\Infrastructure\Behat\Context;

use Assert\Assert;
use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Kata\Tests\Shared\Infrastructure\Behat\Utils\ContextTools;
use Kata\Tests\Shared\Infrastructure\Behat\Utils\ResponsePool;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class SymfonyHttpContext implements Context
{
    private ?Request $request = null;
    private array $params = [];
    private array $headers = [];
    private string $host = '';

    public function __construct(
        private KernelInterface $kernel,
        private ResponsePool $responsePool,
    ) {
    }

    /**
     * @When I prepare a :arg1 request to :arg2
     */
    public function iPrepareARequestTo(string $method, string $uri, string $body = null): void
    {
        $methods = [
            Request::METHOD_CONNECT,
            Request::METHOD_DELETE,
            Request::METHOD_GET,
            Request::METHOD_PATCH,
            Request::METHOD_POST,
            Request::METHOD_PUT,
        ];
        Assert::that(strtoupper($method))->inArray($methods);

        $this->request = Request::create($uri, $method, [], [], [], [], $body);
    }

    /**
     * @Given I set the :key header to :value
     */
    public function iPrepareTheHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * @When I send a :method request to :uri
     */
    public function iSendAMethodRequestTo(string $method, string $uri): void
    {
        $this->iPrepareARequestTo($method, $uri);
        $this->iSendTheRequest();
    }

    /**
     * @When I prepare a :arg1 request to :arg2 with body:
     */
    public function iPrepareARequestToWithBody(string $method, string $uri, PyStringNode $body): void
    {
        $uri = ContextTools::replaceDynamicFields($uri);
        $body = ContextTools::replaceDynamicFields((string)$body);
        $this->iPrepareARequestTo($method, $uri, $body);
    }

    /**
     * @When I send a :method request to :uri with body:
     */
    public function iSendTheMethodRequestToWithBody(string $method, string $uri, PyStringNode $body): void
    {
        $this->iPrepareARequestToWithBody($method, $uri, $body);
        $this->iSendTheRequest();
    }

    /**
     * @When I send a :method request to :uri with JSON body:
     */
    public function iSendTheMethodRequestToWithJsonBody(string $method, string $uri, PyStringNode $body): void
    {
        $this->iPrepareARequestToWithBody($method, $uri, $body);
        $this->iPrepareTheHeader('Content-Type', 'application/json');
        $this->iSendTheRequest();
    }

    /**
     * @When I prepare a :method request to :uri with form data:
     */
    public function iPrepareARequestToWithFormData(string $method, string $uri, TableNode $formData): void
    {
        $params = $formData->getHash()[0];
        $this->request = Request::create($uri, $method, $params);
    }

    /**
     * @When I send a :method request to :uri with form data:
     */
    public function iSendARequestToWithFormData(string $method, string $uri, TableNode $formData): void
    {
        $this->iPrepareARequestToWithFormData($method, $uri, $formData);
        $this->iSendTheRequest();
    }

    /**
     * @When I prepare a :method request to :uri with parameters:
     */
    public function iPrepareARequestToWithParameters(string $method, string $uri, TableNode $parameters): void
    {
        $this->prepareRequest($method, $uri, $parameters->getHash()[0]);
    }

    /**
     * @When I send a :method request to :uri with parameters:
     */
    public function iSendARequestToWithParameters(string $method, $uri, TableNode $parameters): void
    {
        $this->prepareRequest($method, $uri, $parameters->getHash()[0]);
        $this->iSendTheRequest();
    }

    /**
     * @When I send the request
     */
    public function iSendTheRequest(Request $request = null): void
    {
        $this->iPrepareTheHeader('HOST', $this->host);

        $request = $request ?? $this->request;
        if (!empty($this->headers)) {
            $request->headers->add($this->headers);
        }
        $response = $this->kernel->handle($request);
        $this->responsePool->store($response);
    }

    /**
     * @Then the response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe(int $statusCode): void
    {
        Assertion::eq(
            $this->getResponse()->getStatusCode(),
            $statusCode,
            'Response status "%s" is not the expected "%s"'
        );
    }

    /**
     * @Then the response status code should be :statusCode with empty body
     */
    public function theResponseStatusCodeShouldBeWithEmptyBody(int $statusCode): void
    {
        $this->theResponseStatusCodeShouldBe($statusCode);
        $this->responseBodyShouldBe(new PyStringNode([''], 0));
    }

    /**
     * @Then the response status code should be :statusCode with body:
     */
    public function theResponseStatusCodeShouldBeWithBody(int $statusCode, PyStringNode $expectedBody): void
    {
        $this->theResponseStatusCodeShouldBe($statusCode);
        $this->responseBodyShouldBe($expectedBody);
    }

    /**
     * @Then the response header content-type should contain :contentType
     */
    public function theResponseHeaderContentTypeShouldContain(string $contentType): void
    {
        $value = $this->getResponse()->headers->get('Content-Type');
        $contentTypes = explode(';', $value);
        Assert::that($contentType)->inArray($contentTypes);
    }

    /**
     * @Then the response header content-language should be :locale
     */
    public function theResponseHeaderContentLanguageShouldBe(string $expectedLocale): void
    {
        Assertion::eq($expectedLocale, $this->getResponse()->headers->get('Content-Language'));
    }

    /**
     * @Then the response body should be
     */
    private function responseBodyShouldBe(PyStringNode $content): void
    {
        Assert::that($this->getResponse()->getContent())->eq($content->getRaw());
    }

    /**
     * @Then the response body should be empty
     */
    public function theResponseBodyShouldBeEmpty(): void
    {
        $this->responseBodyShouldBe(new PyStringNode([''], 0));
    }

    /**
     * @Then the response body should contain :content
     */
    public function responseBodyShouldContain(string $content): void
    {
        Assert::that($this->getResponse()->getContent())->contains($content);
    }

    /**
     * @Then the response headers should contain location :location
     */
    public function theResponseHeadersShouldContainLocation(string $location): void
    {
        $locationHeader = $this->getResponse()->headers->get("Location", null);
        Assert::that($locationHeader)->notNull('Location header not found');
        Assert::that($locationHeader)->contains($location);
    }

    /**
     * @When I get :path
     */
    public function iGet(string $path): void
    {
        $this->iSendAMethodRequestTo('GET', $path);
    }

    /**
     * @When I get :path with parameters:
     */
    public function iGetWithParameters(string $path, TableNode $parameters): void
    {
        $this->iPrepareARequestToWithParameters(Request::METHOD_GET, $path, $parameters);
        $this->iSendTheRequest();
    }

    /**
     * @When I post :path with parameters:
     */
    public function iPostWithParameters(string $path, TableNode $parameters): void
    {
        $this->iPrepareARequestToWithParameters(Request::METHOD_POST, $path, $parameters);
        $this->iSendTheRequest();
    }

    /**
     * @Given an empty set of request parameters
     */
    public function aEmptySetOfParameterRequest(): void
    {
        $this->params = [];
    }

    /**
     * @Given I add a parameter request with name :parameter and value :value
     */
    public function iAddAParameterRequestWithNameAndValue(string $parameter, mixed $value): void
    {
        $this->params[$parameter] = $value;
    }

    /**
     * @Given I remove a parameter request with name :parameter
     */
    public function iRemoveAParameterRequestWithName(string $parameter): void
    {
        unset($this->params[$parameter]);
    }

    /**
     * @Given I post :path with previous parameters set
     */
    public function iPostWithPreviousParametersSet($path): void
    {
        $this->prepareRequest('POST', $path, $this->params);
        $this->iSendTheRequest();
    }

    /**
     * @Then print last response
     */
    public function printLastResponse(): void
    {
        echo "Printing last response: " . PHP_EOL;
        echo print_r($this->responsePool->retrieve()->getContent(), true);
    }

    protected function getResponse(): ?Response
    {
        return $this->responsePool->retrieve();
    }

    private function prepareRequest(string $method, string $uri, array $parameters): void
    {
        $this->request = Request::create($uri, $method, $parameters, [], [], [], null);
    }
}
