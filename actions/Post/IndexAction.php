<?php

namespace app\actions\Post;

use app\models\Emoji;
use app\models\forms\PostForm;
use app\models\Post;
use app\resources\PostInfo;
use Yii;
use yii\web\Response;

class IndexAction extends \yii\base\Action
{
    public $postRepository;

    public function run()
    {
        $emojis = Emoji::getEmojis();

        $all_posts = Post::find()->orderBy(['created_at' => SORT_DESC])->with('postEmojis')->all();
        $posts = $this->postRepository->getPostsWithReactions($all_posts);

        $model = new PostForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->validate()) {
                $__post = $model->createPost();
                $time = $model->getLastIPPostTime($__post);

                if ($time !== null) {
                    return ['success' => false, 'message' => 'Вы сможете отправить сообщение через ' . $time . '.'];
                }

                try {
                    $post = $this->postRepository->save($__post);
                    $post = Post::find()->where(['id' => $post->id])->with('postEmojis')->one();
                    $post = $this->postRepository->getPostWithReactions($post);
                    $post = PostInfo::resource($post);

                    return [
                        'success' => true,
                        'message' => 'Пост успешно добавлен.',
                        'part' => $this->controller->renderPartial('partial/__card-post', ['post' => $post, 'emojis' => $emojis]),
                    ];

                } catch (\Exception $e) {
                    return ['success' => false, 'message' => 'Ошибка при сохранении поста.', 'errors' => $e->getMessage()];
                }
            } else {
                return ['success' => false, 'message' => 'Ошибка при сохранении поста.', 'errors' => $model->getErrors()];
            }
        }
        $posts = PostInfo::collection($posts);

        return $this->controller->render('index', compact('posts', 'model', 'emojis'));
    }
}