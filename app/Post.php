<?php

namespace App;

use App\Services\Markdowner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Post
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $subtitle
 * @property string $content_raw
 * @property string $content_html
 * @property string $page_image
 * @property string $meta_description
 * @property int $is_draft
 * @property string $layout
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereContentHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereContentRaw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereIsDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post wherePageImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $licenses 署名描述
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereLicenses($value)
 */
class Post extends Model
{
    protected $fillable = [
        'title', 'content', 'slug', 'published_at', 'licenses_name', 'licenses_link',
    ];

    protected $dates = ['published_at'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag_pivot');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (!$this->exists) {
            $this->attributes['slug'] = str_slug($value);
        }
    }

    public function setContentRawAttribute($value)
    {
        $markdown = new Markdowner();

        $this->attributes['content_raw']  = $value;
        $this->attributes['content_html'] = $markdown->toHTML($value);
    }

    public function syncTags(array $tags)
    {
        Tag::addNeededTags($tags);

        if (count($tags)) {
            $this->tags()->sync(
                Tag::whereIn('tag', $tags)->get()->pluck('id')->all()
            );
            return;
        }

        $this->tags()->detach();
    }

    public function url(Tag $tag = null)
    {
        $url = url('posts/' . $this->slug);
        if ($tag) {
            $url .= '?tag=' . urlencode($tag->tag);
        }

        return $url;
    }

    public function tagLinks($base = '/posts?tag=%TAG%')
    {
        $tags   = $this->tags()->get()->pluck('tag')->all();
        $return = [];
        foreach ($tags as $tag) {
            $url      = str_replace('%TAG%', urlencode($tag), $base);
            $return[] = '<a href="' . $url . '">' . e($tag) . '</a>';
        }
        return $return;
    }

    public function newerPost(Tag $tag = null)
    {
        $query = static::where('published_at', '>', $this->published_at)
            ->where('published_at', '<=', Carbon::now())
            ->where('is_draft', 0)
            ->orderBy('published_at', 'asc');
        if ($tag) {
            $query = $query->whereHas('tags', function ($query) use ($tag) {
                $query->where('tag', '=', $tag->tag);
            });
        }

        return $query->first();
    }

    public function olderPost(Tag $tag = null)
    {
        $query = static::where('published_at', '<', $this->published_at)
            ->where('is_draft', 0)
            ->orderBy('published_at', 'desc');
        if ($tag) {
            $query = $query->whereHas('tags', function ($query) use ($tag) {
                $query->where('tag', '=', $tag->tag);
            });
        }

        return $query->first();
    }

    protected function setUniqueSlug($title, $extra)
    {
        $slug = str_slug($title . '-' . $extra);

        if (static::where('slug', $slug)->exists()) {
            $this->setUniqueSlug($title, $extra + 1);
            return;
        }

        $this->attributes['slug'] = $slug;
    }
}
