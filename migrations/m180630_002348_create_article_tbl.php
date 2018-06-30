<?php

use yii\db\Migration;

/**
 * Class m180630_002348_create_article_tbl
 */
class m180630_002348_create_article_tbl extends Migration
{
    const TBL_NAME = '{{%article}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME, [
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'title' => $this->string(125)->notNull()->defaultValue('')->comment('文章标题'),
            'brief' => $this->string(512)->notNull()->defaultValue('')->comment('文章简介'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('文章类型'),
            'words' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('字数'),

            'recommend' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('推荐'),
            'draft' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('草稿箱'),
            'recycle' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('回收站'),

            'visited' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('浏览次数'),
            'comment' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论次数'),
            'likes' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('喜欢次数'),
            'collect' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('收藏次数'),

            'topic_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属话题'),
            'content_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章内容'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('作者'),

            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),

        ], 'engine=innodb default charset=utf8');

        //创建索引
        $this->createIndex(
            'idx-article-created_at',
            self::TBL_NAME,
            'created_at'
        );


       /*
        $this->addForeignKey(
            'fk-article-topic_id',
            self::TBL_NAME,
            'topic_id',
            '{{%topic}}',
            'id',
            'NO ACTION'
        );
       $this->addForeignKey(
            'fk-article-content_id',
            self::TBL_NAME,
            'content_id',
            '{{%content}}',
            'id',
            'NO ACTION'
        );
       $this->addForeignKey(
            'fk-article-user_id',
            self::TBL_NAME,
            'user_id',
            '{{%user}}',
            'id',
            'NO ACTION'
        );
       */

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
        echo "m180630_002348_create_article_tbl cannot be reverted.\n";

        return false;
    }
    */
}
