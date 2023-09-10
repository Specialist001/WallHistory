<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m230908_195330_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // MySQL-specific code
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%posts}}', [
            'id' => $this->bigPrimaryKey(),
            'author' => $this->string(15)->notNull()->comment("min: 2, max: 15 symbols"),
            'content' => $this->text()->null()->comment("min: 2, max: 1000 symbols"),
            'ip' => $this->string(255)->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%posts}}');
    }
}
