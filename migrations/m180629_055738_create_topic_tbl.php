<?php

use yii\db\Migration;

/**
 * Class m180629_055738_create_topic_tbl
 */
class m180629_055738_create_topic_tbl extends Migration
{
    const TBL_NAME = '{{%topic}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'name' => $this->string(18)->notNull()->defaultValue('')->comment('话题名'),
            'desc' => $this->string(35)->notNull()->defaultValue('')->comment('描述'),
            'image' => $this->string(125)->notNull()->defaultValue('')->comment('图片'),
            'count' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章总数'),
            'finished' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('是否完结'),
            'category_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属分类'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建者'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),
        ],'engine=innodb charset=utf8');

        //创建索引
        $this->createIndex(
            'idx-topic-name',
            self::TBL_NAME,
            'name'
        );

        /*$this->addForeignKey(
            'fk-topic-category_id',
            self::TBL_NAME,
            'category_id',
            '{{%category}}',
            'id',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-topic-user_id',
            self::TBL_NAME,
            'user_id',
            '{{%user}}',
            'id',
            'NO ACTION'
        );*/


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
        echo "m180629_055738_create_topic_tbl cannot be reverted.\n";

        return false;
    }
    */
}
