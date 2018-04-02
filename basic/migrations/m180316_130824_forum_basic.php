<?php

use yii\db\Migration;

/**
 * Class m180316_130824_forum_basic
 */
class m180316_130824_forum_basic extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'mail' => $this->string(45)->unique(),
            'pass' => $this->string(255),
            'first_name' => $this->text(),
            'last_name' => $this->string(45),
            'nick_name' => $this->string(45)->unique(),
        ]);

        $this->createTable('themes', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->unique(),
            'closed' => $this->tinyInteger(2),
            'user_id' => $this->integer(11),
            'group_id' => $this->integer(2),
        ]);

        $this->createTable('groups', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->unique(),
        ]);

        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'content' => $this->text(),
            'user_id' => $this->integer(11),
            'theme_id' => $this->integer(11),
            'likes' => $this->integer(11),
        ]);

        $this->createTable('comment_liked', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'comment_id' => $this->integer(11),
            'status' => $this->tinyInteger(2),
        ]);

        $this->addForeignKey(
            'fk-themes-user_id',
            'themes',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-comments-user_id',
            'comments',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-comment_liked-user_id',
            'comment_liked',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-comments-theme_id',
            'comments',
            'theme_id',
            'themes',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-themes-group_id',
            'themes',
            'group_id',
            'groups',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-comment_liked-comment_id',
            'comment_liked',
            'comment_id',
            'comments',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-themes-user_id',
            'themes'
        );

        $this->dropForeignKey(
            'fk-comments-user_id',
            'comments'
        );

        $this->dropForeignKey(
            'fk-comment_liked-user_id',
            'comment_liked'
        );

        $this->dropForeignKey(
            'fk-comments-theme_id',
            'comments'
        );

        $this->dropForeignKey(
            'fk-themes-group_id',
            'themes'
        );

        $this->dropForeignKey(
            'fk-comment_liked-comment_id',
            'comment_liked'
        );

        $this->dropTable('comment_liked');
        $this->dropTable('comments');
        $this->dropTable('groups');
        $this->dropTable('themes');
        $this->dropTable('users');
    }
}
