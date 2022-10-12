<?php

namespace App\Resolver;


use ApiPlatform\GraphQl\Resolver\QueryItemResolverInterface;
use App\Entity\Genre;

final class GenreResolver implements QueryItemResolverInterface
{

    /**
     * @param Genre | null $item
     * @param array $context
     * @return Genre | null
     */
    public function __invoke($item, array $context): ?Genre
    {
        return $item;
    }
}