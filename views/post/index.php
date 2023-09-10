<?php
/** @var yii\web\View $this */
/** @var app\models\forms\PostForm $model */
/** @var app\models\Emoji[] $emojis */

/** @var app\models\Post[] $posts */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'История';

$cs = <<<CSS
    #create-post {
        position: -webkit-sticky;
        position: sticky;
        top: 5rem;
        right: 0;
        z-index: 2;
        height: calc(100vh - 10rem);
        overflow-y: auto;
    }
CSS;

$this->registerCss($cs);


?>
<div class="row">
    <div class="col-12 d-md-none">
        <button id="toggleCreatePost" class="btn btn-primary">Оставить пост</button>
    </div>
    <div class="col-md-6">
        <h1>История</h1>
        <div class="scrollable-content">
            <div id="posts">
                <?php if ($posts): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="card card-default mb-2">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo Html::encode($post['author']); ?></h5>
                                <p><?php echo $post['content']; ?></p>
                                <p>
                                    <small class="text-muted">
                                        <?php echo $post['created_at'] ?>
                                        | <?php echo Html::encode($post['masked_ip']) ?>
                                    </small>
                                </p>
                                <div class="d-inline-block w-100 emoji-list" data-post_id="<?php echo $post['id']; ?>">
                                    <?php foreach ($emojis as $emoji) { ?>
                                        <?php if (!empty($post['reactions'])) {
                                            if (array_key_exists($emoji['id'], $post['reactions'])) { ?>
                                                <span class="post-action me-1 d-inline-block mb-0"
                                                      data-emoji_id="<?php echo $emoji['id'] ?>">
                                                    <?php echo $post['reactions'][$emoji['id']]['reaction_count'] . "x" . $post['reactions'][$emoji['id']]['emoji_code'] ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="post-action me-1 d-inline-block mb-0"
                                                      data-emoji_id="<?php echo $emoji['id'] ?>">
                                                    <?php echo $emoji['code'] ?>
                                                </span>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <span class="post-action me-1 d-inline-block mb-0"
                                                  data-emoji_id="<?php echo $emoji['id'] ?>">
                                                <?php echo $emoji['code'] ?>
                                            </span>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else: ?>
                    <p id="post-none">Постов пока нет.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 create-post-block d-none d-md-block">
        <div class="sticky-top" id="create-post">
            <?php
            $form = ActiveForm::begin(
                [
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => true,
                    'id' => 'my-ajax-form',
                    'options' => ['class' => 'form-horizontal'],
                ]
            ); ?>

            <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::class, [
                'captchaAction' => 'site/captcha', // Путь к действию, где будет генерироваться капча
            ]) ?>


            <div class="form-group">
                <?= Html::submitButton('Добавить пост', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
<!-- JS code -->
<?php
$this->registerJsFile('/js/post.js', ['depends' => [\yii\web\YiiAsset::class]]);
?>

