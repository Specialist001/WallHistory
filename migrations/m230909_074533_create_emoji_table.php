<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%emoji}}`.
 */
class m230909_074533_create_emoji_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%emoji}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->null(),
            'code' => $this->string(128)->null(),
            'image' => $this->string(255)->null(),
            'order' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%emoji}}');
    }
}
