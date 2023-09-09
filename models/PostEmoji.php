<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "post_emoji".
 *
 * @property int $id
 * @property int $post_id
 * @property int $emoji_id
 * @property string $user_ip
 * @property int|null $created_at
 *
 * @property Emoji $emoji
 * @property Post $post
 */
class PostEmoji extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_emoji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'emoji_id', 'user_ip'], 'required'],
            [['post_id', 'emoji_id', 'created_at'], 'default', 'value' => null],
            [['post_id', 'emoji_id', 'created_at'], 'integer'],
            [['user_ip'], 'string', 'max' => 100],
            [['post_id', 'emoji_id', 'user_ip'], 'unique', 'targetAttribute' => ['post_id', 'emoji_id', 'user_ip']],
            [['emoji_id'], 'exist', 'skipOnError' => true, 'targetClass' => Emoji::class, 'targetAttribute' => ['emoji_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'emoji_id' => 'Emoji ID',
            'user_ip' => 'User Ip',
            'created_at' => 'Created At',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', false],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => false,

                ],

            ]
        ];
    }

    /**
     * Gets query for [[Emoji]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmoji()
    {
        return $this->hasOne(Emoji::class, ['id' => 'emoji_id']);
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }



}
