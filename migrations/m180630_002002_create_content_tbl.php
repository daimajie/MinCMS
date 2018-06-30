<?php

use yii\db\Migration;

/**
 * Class m180630_002002_create_content_tbl
 */
class m180630_002002_create_content_tbl extends Migration
{
    const TBL_NAME = '{{%content}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'content' => $this->text()->comment('文章年内容 '),
        ],'engine=innodb default charset=utf8');
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
        echo "m180630_002002_create_content_tbl cannot be reverted.\n";

        return false;
    }
    */
}
