<?php

namespace app\models\forms;

use app\components\SiteHelper;
use app\models\Post;
use yii\base\Model;

class PostForm extends Model
{
    const LIMIT_TIME = 180;

    public $author;
    public $content;
    public $captcha;

    public function rules()
    {
        return [
            [['author', 'content'], 'required'],
            ['captcha', 'captcha'],
            ['author', 'string', 'min' => 2, 'max' => 15],
            ['content', 'string', 'min' => 5, 'max' => 1000],
        ];
    }

    // labels
    public function attributeLabels()
    {
        return [
            'author' => 'Автор',
            'content' => 'Сообщение',
            'captcha' => 'Код с картинки',
        ];
    }

    public function createPost()
    {
        $post = new Post();
        $post->author = $this->author;
        $post->content = $this->content;
        $post->created_at = time();

        return $post;
    }

    public function getLastIPPostTime($post)
    {
        \Carbon\CarbonInterval::setLocale(\Yii::$app->language);

        $post->ip = SiteHelper::getUserIP();

        // if this IP has already posted in last 3 minutes, then return access time to post again
        $lastPost = Post::find()->where(['ip' => $post->ip])->orderBy(['created_at' => SORT_DESC])->one();
        if ($lastPost) {
            $time = time() - $lastPost->created_at;
            if ($time < self::LIMIT_TIME) {
                $seconds = self::LIMIT_TIME - $time;
                $interval = \Carbon\CarbonInterval::seconds($seconds);

                return $interval->cascade()->forHumans(['join' => true]);
            }
        }

        return null;
    }
}