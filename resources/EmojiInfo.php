<?php

namespace app\resources;

use app\models\Emoji;

class EmojiInfo
{
    public static function collection($emojis)
    {
        $data = [];
        $loop = 0;
        foreach ($emojis as $emoji) {
            $data[$loop] = self::transform($emoji);
            $loop++;
        }

        return $data;
    }

    public static function resource($emoji)
    {
        return self::transform($emoji);
    }

    /**
     * @param Emoji $post
     * @return array
     */
    public static function transform($emoji)
    {
        return [
            'id' => $emoji->id,
            'name' => $emoji->name,
            'code' => $emoji->code,
            'order' => $emoji->order,
        ];
    }
}