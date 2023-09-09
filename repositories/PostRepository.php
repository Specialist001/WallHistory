<?php

namespace app\repositories;

use app\components\SiteHelper;
use app\models\Post;
use app\models\PostEmoji;
use yii\db\StaleObjectException;

class PostRepository
{
    /**
     * Find Post by id.
     *
     * @param int $id
     * @return Post|null
     */
    public function findById($id)
    {
        return Post::findOne($id);
    }

    /**
     * Save Post.
     *
     * @param Post $post
     * @return Post|null
     */
    public function save(Post $post): ?Post
    {
        if ($post->save()) {
            return $post;
        }

        return null;
    }

    /**
     * Delete Post.
     *
     * @param Post $post
     * @return bool
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function delete(Post $post)
    {
        return $post->delete();
    }

    public function addEmoji(Post $post, \app\models\Emoji $emoji)
    {
        // if this user IP has already added...
        $post_emoji = PostEmoji::find()->where(['post_id' => $post->id, 'user_ip' => SiteHelper::getUserIP()])->one();
        if ($post_emoji) {
            // This emoji to this post (post_emoji table) then delete it
            if ($post_emoji->emoji_id == $emoji->id) {
                $post_emoji->delete();
            } else {
                // Other emoji to this post, then delete it and add new
                $post_emoji->emoji_id = $emoji->id;
                $post_emoji->save();
            }
        } else {
            // if this user IP has not added any emoji to this post, then add new
            $new_reaction = new PostEmoji([
                'post_id' => $post->id,
                'emoji_id' => $emoji->id,
                'user_ip' => SiteHelper::getUserIP(),
            ]);
            $new_reaction->save();
        }
    }

    public function getPostsWithReactions($posts)
    {
        $resultArray = [];
        foreach ($posts as $post) {
            $result = [
                'id' => $post->id,
                'author' => $post->author,
                'content' => $post->content,
                'ip' => $post->ip,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'reactions' => [],
            ];

            foreach ($post->postEmojis as $postEmoji) {

                $emojiId = $postEmoji->emoji_id;
                $emojiCode = $postEmoji->emoji->code;

                if (isset($result['reactions'][$emojiId])) {
                    $result['reactions'][$emojiId]['reaction_count']++;
                } else {
                    $result['reactions'][$emojiId] = [
                        'emoji_id' => $emojiId,
                        'emoji_code' => $emojiCode,
                        'reaction_count' => 1,
                    ];
                }
                // if $postEmoji->emoji_id dup
            }

            $resultArray[] = $result;
        }

        return $resultArray;
    }

    public function getPostWithReactions(?Post $post)
    {
        $result = [
            'id' => $post->id,
            'author' => $post->author,
            'content' => $post->content,
            'ip' => $post->ip,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'reactions' => [],
        ];

        foreach ($post->postEmojis as $postEmoji) {

            $emojiId = $postEmoji->emoji_id;
            $emojiCode = $postEmoji->emoji->code;

            if (isset($result['reactions'][$emojiId])) {
                $result['reactions'][$emojiId]['reaction_count']++;
            } else {
                $result['reactions'][$emojiId] = [
                    'emoji_id' => $emojiId,
                    'emoji_code' => $emojiCode,
                    'reaction_count' => 1,
                ];
            }
        }

        return $result;
    }
}