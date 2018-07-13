<?php

use yii\db\Migration;

/**
 * Class m180713_114646_create_collect_tbl
 */
class m180713_114646_create_collect_tbl extends Migration
{
    const TBL_NAME = '{{%collect}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('用户'),
            'article_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('类型'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
        ],'engine=innodb default charset=utf8');

        $this->createIndex(
            'idx-collect-user_id',
            static::TBL_NAME,
            'user_id'
        );

        $this->createIndex(
            'idx-collect-article_id',
            static::TBL_NAME,
            'article_id'
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
        echo "m180713_114646_create_collect_tbl cannot be reverted.\n";

        return false;
    }
    */
}
