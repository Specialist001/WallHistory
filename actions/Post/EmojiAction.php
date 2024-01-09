<?php

namespace app\actions\Post;

use app\models\Emoji;
use app\models\PostEmoji;
use Yii;
use yii\web\Response;

class EmojiAction extends \yii\base\Action
{
    public $postRepository;

    public function run()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $post_request = Yii::$app->request->post();
            $post_id = $post_request['post_id'];
            $emoji_id = $post_request['emoji_id'];

            $post = $this->postRepository->findById($post_id);
            $emoji = Emoji::findOne($emoji_id);

            if (!$post) {
                return ['success' => false, 'message' => 'Пост не существует.'];
            }

            if (!$emoji) {
                return ['success' => false, 'message' => 'Эмодзи не существует.'];
            }

            $this->postRepository->addEmoji($post, $emoji);

            $emoji_counts = $this->getEmojiCounts($post_id);


            return ['success' => true, 'message' => 'Реакция добавлена.', 'reactions' => $emoji_counts];
        }

        return false;
    }

    private function getEmojiCounts($post_id)
    {
        $reactions = PostEmoji::find()
            ->select(['emoji_id', 'COUNT(*) AS count'])
            ->where(['post_id' => $post_id])
            ->groupBy('emoji_id')
            ->asArray()
            ->all();

        // return all emojis merge with reactions
        $emojis = Emoji::getEmojisArray();

        $emojiCounts = [];
        foreach ($emojis as $key => $emoji) {
            $emojiCounts[$key] = 0;
        }

        foreach ($reactions as $reaction) {
            $emojiCounts[$reaction['emoji_id']] = $reaction['count'];
        }

        foreach ($emojiCounts as $emoji_id => $count) {
            $emojiCounts[$emoji_id] = $count > 0 ? $count . 'x' . $emojis[$emoji_id]['code'] : $emojis[$emoji_id]['code'];
        }

        return $emojiCounts;
    }

}
