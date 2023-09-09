<?php

namespace app\models;


/**
 * This is the model class for table "emoji".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $image
 * @property int|null $order
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property PostEmoji[] $postEmojis
 */
class Emoji extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'emoji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['order', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 128],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'image' => 'Image',
            'order' => 'Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
            ],
        ];
    }

    /**
     * Gets query for [[PostEmojis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostEmojis()
    {
        return $this->hasMany(PostEmoji::class, ['emoji_id' => 'id']);
    }

    // get all emojis by order asc
    public static function getEmojis()
    {
        return self::find()->orderBy(['order' => SORT_ASC])->all();
    }

    public static function getEmojisArray()
    {
        return self::find()->orderBy(['order' => SORT_ASC])
            ->indexBy('id')
            ->asArray()->all();
    }
}
