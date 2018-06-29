<?php

use yii\db\Migration;

/**
 * Class m180628_002023_create_category_tbl
 */
class m180628_002023_create_category_tbl extends Migration
{
    const TBL_NAME = '{{%category}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'name' => $this->string(18)->notNull()->defaultValue('')->comment('分类名'),
            'desc' => $this->string(255)->notNull()->defaultValue('')->comment('描述'),
            'image' => $this->string(125)->notNull()->defaultValue('')->comment('图片'),
            'count' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('话题总数'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),
        ],'engine=innodb charset=utf8');


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
        echo "m180628_002023_create_category_tbl cannot be reverted.\n";

        return false;
    }
    */
}
