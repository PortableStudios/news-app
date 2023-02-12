<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * 
 * @property-read int $id
 * @property-read int content_ref_id
 * @property-read string title
 * @property-read string article_url
 * @property-read string published_date
 * @property-read boolean is_pinned 
 */
class NewsSearchResults extends Model
{
    /**
     * Timestamps
     */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news_search_results';

    /**
     * The primary key associated with the table.
     *
     * @var int
     */
    protected $primaryKey = 'id';

    protected $dates    = ['published_date', 'created_at', 'updated_at'];

    protected $fillable = ['content_ref_id', 'title', 'article_url', 'published_date', 'is_pinned'];
}