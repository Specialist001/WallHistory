<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post_emoji}}`.
 */
class m230909_075418_create_post_emoji_table extends Migration
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

        $this->createTable('{{%post_emoji}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->bigInteger()->notNull(),
            'emoji_id' => $this->integer()->notNull(),
            'user_ip' => $this->string(100)->notNull(),
            'created_at' => $this->integer()->null(),
        ], $tableOptions);

        $this->createIndex(
            'idx-post-emoji-user_ip-unique',
            'post_emoji',
            ['post_id', 'emoji_id', 'user_ip'],
            true
        );

        $this->addForeignKey('fk-post-emoji-post_id','post_emoji','post_id',
            'posts','id',
            'CASCADE','CASCADE'
        );

        $this->addForeignKey('fk-post-emoji-emoji_id','post_emoji','emoji_id',
            'emoji','id',
            'CASCADE','CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-post-emoji-post_id','post_emoji');
        $this->dropForeignKey('fk-post-emoji-emoji_id','post_emoji');
        $this->dropIndex('idx-post-emoji-user_ip-unique','post_emoji');
        
        $this->dropTable('{{%post_emoji}}');
    }
}
