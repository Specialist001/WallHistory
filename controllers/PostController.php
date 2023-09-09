<?php

namespace app\controllers;

use app\repositories\PostRepository;
use Carbon\Carbon;
use Yii;

class PostController extends \yii\web\Controller
{
    private $postRepository;

    public function __construct($id, $module, PostRepository $postRepository, $config = [])
    {
        $this->postRepository = $postRepository;
        Carbon::setLocale(Yii::$app->language);

        parent::__construct($id, $module, $config);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'app\actions\Post\IndexAction',
                'postRepository' => $this->postRepository,
            ],
            'emoji' => [
                'class' => 'app\actions\Post\EmojiAction',
                'postRepository' => $this->postRepository,
            ],
        ];
    }

}
