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
        $code = $this->string(128)->null();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // MySQL-specific code
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci ENGINE=InnoDB';
            $code = $this->binary();
        }


        $this->createTable('{{%emoji}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->null(),
            'code' => $code,
            'image' => $this->string(255)->null(),
            'order' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%emoji}}');
    }
}
