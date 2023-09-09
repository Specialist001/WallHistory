<?php

namespace app\models;

use app\components\SiteHelper;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $author min: 2, max: 15 symbols
 * @property string|null $content min: 2, max: 1000 symbols
 * @property string|null $ip
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author'], 'required'],
            [['content', 'ip'], 'string'],
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'integer'],
            [['author'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author' => 'Author',
            'content' => 'Content',
            'ip' => 'Ip',
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ip = SiteHelper::getUserIP();
            return true;
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function maskedIP(): ?string
    {
        if (filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // If IP is IPv4, hide the last two section
            $parts = explode('.', $this->ip);
            $maskedIP = $parts[0] . '.' . $parts[1] . '.*.*';
        } elseif (filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // If IP is IPv6, hide the last four section
            $parts = explode(':', $this->ip);
            $maskedIP = implode(':', array_slice($parts, 0, -4)) . ':****:****:****:****';
        } else {
            // If IP is neither IPv4 nor IPv6, return the original IP
            $maskedIP = $this->ip;
        }

        return $maskedIP;
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getEmojis()
    {
        return $this->hasMany(Emoji::class, ['id' => 'emoji_id'])
            ->viaTable('post_emoji', ['post_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPostEmojis()
    {
        return $this->hasMany(PostEmoji::class, ['post_id' => 'id'])
         ->with('emoji');
    }




}
