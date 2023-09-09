<?php

use yii\db\Migration;

/**
 * Class m230909_074802_insert_emoji
 */
class m230909_074802_insert_emoji extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $emojies = [
          ["name" => "Like", "code" => "👍"],
          ["name" => "Dislike", "code" => "👎"],
          ["name" => "Love", "code" => "❤️"],
          ["name" => "Sh*t", "code" => "💩"],
        ];

        // insert emoji via Yii2 DAO in loop
        foreach ($emojies as $key => $emoji) {
            $this->insert('{{%emoji}}', [
                'name' => $emoji['name'],
                'code' => $emoji['code'],
                'order' => $key + 1,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230909_074802_insert_emojii cannot be reverted.\n";

        return false;
    }
    */
}
