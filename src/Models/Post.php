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
    const STATUS_PUBLISHED = 'published';

    public function bodyHtml()
    {
        return Parser::html($this->body);
    }

    public function isDraft()
    {
        return $this->published == null;
    }

    public function isPublished()
    {
        return $this->published !== null
            && $this->published->lt(Carbon::now());
    }

    public function isScheduled()
    {
        return $this->published !== null
            && $this->published->gt(Carbon::now());
    }

    public static function nextPostForSubscribers()
    {
        return self::where('published', '<=', Carbon::now()->toDateTimeString())
            ->orderBy('published', 'desc')
            ->first();
    }

    public function scopeDraft($query)
    {
        return $query->where('published', null)
            ->orderBy('updated_at', 'desc');
    }

    public function scopePublished($query)
    {
        return $query->where('published', '<=', Carbon::now()->toDateTimeString())
            ->orderBy('published', 'desc');
    }

    public function scopeScheduled($query)
    {
        return $query->where('published', '>', Carbon::now()->toDateTimeString())
            ->orderBy('published', 'desc');
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
            'published' => Carbon::now()->toDateTimeString()
        ]);
    }

    public function markAsPublished()
    {
        $this->update([
            'status' => self::STATUS_PUBLISHED
        ]);
    }

    public function schedule(Carbon $carbon)
    {
        $this->update([
            'published' => $carbon->toDateTimeString()
        ]);
    }
}
