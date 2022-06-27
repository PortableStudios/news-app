<?php

namespace App\GraphQL\Queries;

use App\Models\Article;

final class SearchArticleResolver
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return Article::where(
            'title', 'LIKE', '%'.$args['query'].'%'
        )->orWhere('sectionID', 'LIKE', '%'.$args['query'].'%')
            ->orWhere('sectionTitle', 'LIKE', '%'.$args['query'].'%')
            ->orWhere('webURL', 'LIKE', '%'.$args['query'].'%')
            ->get();
    }
}
