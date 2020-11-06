<?php

namespace DarkBlog\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DarkBlog\Parser;

class Post extends Model
{
    protected $guarded = [];

    protected $dates = ['published'];

    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_PUBLISHED = 'published';

    public function bodyHtml()
    {
        return Parser::html($this->body);
    }

    public function isDraft()
    {
        return $this->status == static::STATUS_DRAFT;
    }

    public function isPublished()
    {
        return $this->status == static::STATUS_PUBLISHED;
    }

    public function scopeDraft($query)
    {
        return $query->where('status', static::STATUS_DRAFT)
            ->orderBy('updated_at', 'desc');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published', '<=', Carbon::now()->toDateTimeString())
            ->orderBy('updated_at', 'desc');
    }

    public function scopeLatest($query, $limit = null)
    {
        if ($limit) {
            return $query->limit($limit);
        }

        return $query;

    }

    public function scopeTagged($query, $tag)
    {
        return $query->whereHas('tags', function ($query) use ($tag) {
            $query->where('name', $tag);
        });
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'tagged', 'tagged');
    }

    public function publish()
    {
        $this->update([
            'status' => self::STATUS_PUBLISHED,
            'published' => Carbon::now()->toDateTimeString()
        ]);
    }

    public function schedule(Carbon $carbon)
    {
        $this->update([
            'status' => self::STATUS_SCHEDULED,
            'published' => $carbon->toDateTimeString()
        ]);
    }
}
