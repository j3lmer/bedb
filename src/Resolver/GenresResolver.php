<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\QueryCollectionResolverInterface;
Use App\Entity\Genre;

final class GenresResolver implements QueryCollectionResolverInterface
{

    /**
     * @param iterable<Genre> $collection
     * @return iterable<Genre>
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        return $collection;
    }
}