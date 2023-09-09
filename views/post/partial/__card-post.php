<?php
/**
 * @var $post array
 * @var $emojis array
 */

use Carbon\Carbon;
use yii\bootstrap5\Html;

?>

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
                        <span class="post-action me-1 d-inline-block mb-0" data-emoji_id="<?php echo $emoji['id'] ?>">
                            <?php echo $post['reactions'][$emoji['id']]['reaction_count'] . "x" . $post['reactions'][$emoji['id']]['emoji_code'] ?>
                        </span>
                    <?php } else { ?>
                        <span class="post-action me-1 d-inline-block mb-0" data-emoji_id="<?php echo $emoji['id'] ?>">
                            <?php echo $emoji['code'] ?>
                        </span>
                    <?php } ?>
                <?php } else { ?>
                    <span class="post-action me-1 d-inline-block mb-0" data-emoji_id="<?php echo $emoji['id'] ?>">
                        <?php echo $emoji['code'] ?>
                    </span>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>