<?php

use yii\db\Migration;

/**
 * Class m180714_135929_create_comment_tbl
 */
class m180714_135929_create_comment_tbl extends Migration
{
    const TBL_NAME = '{{%comment}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'content' => $this->text()->comment('内容'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('用户'),
            'article_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('类型'),
            'comment_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),

        ],'engine=innodb default charset=utf8');

        //索引
        $this->createIndex(
            'idx-comment-user_id',
            self::TBL_NAME,
            'user_id'
        );

        $this->createIndex(
            'idx-comment-article_id',
            self::TBL_NAME,
            'article_id'
        );

        $this->createIndex(
            'idx-comment-comment_id',
            self::TBL_NAME,
            'comment_id'
        );

        $this->createIndex(
            'idx-comment-created_at',
            self::TBL_NAME,
            'created_at'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TBL_NAME);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180714_135929_create_comment_tbl cannot be reverted.\n";

        return false;
    }
    */
}
