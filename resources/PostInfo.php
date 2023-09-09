<?php

namespace app\resources;

use app\components\SiteHelper;
use app\models\Post;
use Carbon\Carbon;
use HTMLPurifier;

class PostInfo
{
    public static function collection($posts)
    {
        $data = [];
        $loop = 0;
        foreach ($posts as $post) {
            $data[$loop] = self::transform($post);
            $loop++;
        }

        return $data;
    }

    public static function resource($post)
    {
        return self::transform($post);
    }

    /**
     * @param Post $post
     * @return array
     */
    public static function transform($post)
    {
        return [
            'id' => $post['id'],
            'author' => $post['author'],
            'content' => (new HTMLPurifier())->purify($post['content']),
            'created_at' => Carbon::parse($post['created_at'])->diffForHumans(),
            'masked_ip' => SiteHelper::hideIP($post['ip']),
            'reactions' => $post['reactions'],
        ];
    }
}