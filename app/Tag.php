<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Tag
 *
 * @property int $id
 * @property string $tag
 * @property string $title
 * @property string $subtitle
 * @property string $page_image
 * @property string $meta_description
 * @property string $layout
 * @property int $reverse_direction
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag wherePageImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereReverseDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tag extends Model
{
    protected $fillable = [
        'tag', 'title', 'subtitle', 'page_image', 'meta_description','reverse_direction',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag_pivot');
    }

    public static function addNeededTags(array $tags)
    {
        if (count($tags) === 0) {
            return;
        }

        $found = static::whereIn('tag', $tags)->get()->pluck('tag')->all();

        foreach (array_diff($tags, $found) as $tag) {
            static::create([
                'tag' => $tag,
                'title' => $tag,
                'subtitle' => 'Subtitle for '.$tag,
                'page_image' => '',
                'meta_description' => '',
                'reverse_direction' => false,
            ]);
        }
    }

    public static function layout($tag, $default = 'posts.index')
    {
        $layout = static::where('tag', $tag)->get()->pluck('layout')->first();

        return $layout ?: $default;
    }
}
