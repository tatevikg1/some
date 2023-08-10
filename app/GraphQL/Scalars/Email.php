<?php

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\ValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

final class Email extends ScalarType
{
    /**
     * Serializes an internal value to include in a response.
     */
    public function serialize(mixed $value): mixed
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvariantViolation("Could not serialize value as email: " . Utils::printSafe($value));
        }

        return $this->parseValue($value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     * @throws Error
     */
    public function parseValue(mixed $value): mixed
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Error("Cannot represent following value as email: " . Utils::printSafeJson($value));
        }

        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * Should throw an exception with a client friendly message on invalid value nodes, @param ValueNode&Node $valueNode
     * @param array<string, mixed>|null $variables
     * @throws Error
     * @see \GraphQL\Error\ClientAware.
     *
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): mixed
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        if (!filter_var($valueNode->value, FILTER_VALIDATE_EMAIL)) {
            throw new Error("Not a valid email", [$valueNode]);
        }

        return $valueNode->value;
    }
}
