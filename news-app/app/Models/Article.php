<?php

namespace App\Models;

class Article
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'sectionID',
        'sectionTitle',
        'webURL',
        'publicationDate'
    ];
}
