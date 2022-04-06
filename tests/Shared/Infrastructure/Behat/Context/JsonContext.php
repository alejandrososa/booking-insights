<?php

declare(strict_types=1);

namespace Kata\Tests\Shared\Infrastructure\Behat\Context;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Exception;
use Kata\Tests\Shared\Infrastructure\Behat\Utils\ContextTools;
use Kata\Tests\Shared\Infrastructure\Behat\Utils\ResponsePool;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Simplified version of https://packagist.org/packages/behatch/contexts
 */
class JsonContext implements Context
{
    private PropertyAccessor $propertyAccessor;

    /**
     * evaluationMode - javascript "foo.bar" or php "foo->bar"
     */
    public function __construct(
        private ResponsePool $responsePool,
        private string $evaluationMode = 'javascript'
    ) {
        $this->propertyAccessor = new PropertyAccessor(0, 1);
    }

    /**
     * @Then print last JSON response
     */
    public function printLastJsonResponse()
    {
        print_r($this->parseJsonFromRequest());
    }

    /**
     * @Then the response should be this JSON:
     */
    public function theResponseShouldBeThisJson(PyStringNode $pyStringNode, bool $canonically = false): void
    {
        ContextTools::jsonComparatorAssertion(
            $pyStringNode->getRaw(),
            $this->responsePool->retrieve()->getContent(),
            $canonically
        );
        $jsonContentTypes = ['application/json'];
        Assertion::inArray($this->responsePool->retrieve()->headers->get('content-type'), $jsonContentTypes);
    }

    /**
     * @Then the response should be this JSON canonically:
     */
    public function theResponseShouldBeThisJsonCanonical(PyStringNode $pyStringNode): void
    {
        $this->theResponseShouldBeThisJson($pyStringNode, true);
    }

    /**
     * @Then the JSON node :node should be equal to :value
     */
    public function theJsonNodeShouldBeEqualTo($node, $value): void
    {
        Assertion::eq($value, $this->evaluate($node));
    }

    /**
     * @Then the JSON node :node should match :pattern
     */
    public function theJsonNodeShouldMatch($node, $pattern): void
    {
        Assertion::regex($this->evaluate($node), $pattern);
    }

    /**
     * @Then the JSON node :node should be null
     */
    public function theJsonNodeShouldBeNull($node)
    {
        Assertion::null($this->evaluate($node));
    }

    /**
     * @Then the JSON node :node should not be null
     */
    public function theJsonNodeShouldNotBeNull($node): void
    {
        Assertion::notNull($this->evaluate($node));
    }

    /**
     * @Then the JSON node :node should have :count element(s)
     */
    public function theJsonNodeShouldHaveElements($node, int $count)
    {
        Assertion::count((array)$this->evaluate($node), $count);
    }

    /**
     * @Then the JSON node :node should be an array
     */
    public function theJsonNodeShouldBeAnArray($node)
    {
        Assertion::isArray($this->evaluate($node));
    }

    /**
     * @Then the JSON node :node should be an object
     */
    public function theJsonNodeShouldBeAnObject($node): void
    {
        Assertion::isObject($this->evaluate($node));
    }

    /**
     * @Then the JSON node :node should be an empty array
     */
    public function theJsonNodeShouldBeAnEmptyArray($node): void
    {
        Assertion::isArray($this->evaluate($node));
        Assertion::count((array)$this->evaluate($node), 0);
    }

    /**
     * @Then the JSON node :node should contain :text
     */
    public function theJsonNodeShouldContain($node, $text): void
    {
        Assertion::contains($this->evaluate($node), $text);
    }

    /**
     * @Then the JSON node :name should exist
     */
    public function theJsonNodeShouldExist(string $name): void
    {
        try {
            $this->evaluate($name);
        } catch (Exception $e) {
            throw new Exception(sprintf('The node "%s" does not exist.', $name));
        }
    }

    /**
     * @Then the JSON node :name should not exist
     */
    public function theJsonNodeShouldNotExist($name): void
    {
        try {
            $this->evaluate($name);
        } catch (Exception $e) {
            return;
        }
        throw new Exception(sprintf('The node "%s" does exist.', $name));
    }

    private function evaluate(string $expression): mixed
    {
        try {
            $data = (object)$this->parseJsonFromRequest();
            if ($this->evaluationMode === 'javascript') {
                $expression = str_replace('->', '.', $expression);
            }
            if (is_array($data)) {
                $expression = preg_replace('/^root/', '', $expression);
            } else {
                $expression = preg_replace('/^root./', '', $expression);
            }
            // If root asked, we return the entire content
            if (strlen(trim($expression)) <= 0) {
                return $data;
            }

            return $this->propertyAccessor->getValue($data, $expression);
        } catch (Exception $e) {
            throw new Exception(sprintf('Failed to evaluate expression "%s"', $expression));
        }
    }

    private function parseJsonFromRequest(): ?array
    {
        return ContextTools::jsonDecode($this->responsePool->retrieve()->getContent());
    }
}
