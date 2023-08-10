<?php

namespace App\GraphQL\Directives;

use App\Models\User;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class CanAccessDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<GRAPHQL
"""
Limit field access to users of a certain role.
"""
directive @canAccess(
  """
  The name of the role authorized users need to have.
  """
  requiredRole: String!
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $fieldValue->wrapResolver(fn (callable $resolver) => function (mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {
            $requiredRole = $this->directiveArgValue('requiredRole');

            if ($requiredRole === null) {
                throw new DefinitionException("Missing argument 'requiredRole' for directive '@canAccess'.");
            }

            /** @var User $user*/
            $user = $context->user();
            if (! $user || $user->role !== constant(sprintf('%s::%s', User::class, $requiredRole)))
            {
                return 'Hahaha, nice try';
            }

            return $resolver($root, $args, $context, $resolveInfo);
        });
    }
}

