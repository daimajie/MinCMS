<?php

use yii\db\Migration;

/**
 * Class m180708_014443_create_metadata_tbl
 */
class m180708_014443_create_metadata_tbl extends Migration
{
    const TBL_NAME = '{{%metadata}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME, [
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'name' => $this->string(18)->notNull()->defaultValue('')->comment('站点名称'),
            'keywords' => $this->string(255)->notNull()->defaultValue('')->comment('关键字'),
            'description' => $this->string(512)->notNull()->defaultValue('')->comment('站点描述'),
            'updated_at' => $this->integer()->unsigned()->defaultValue(0)->comment('修改时间'),
        ], 'engine=innodb default charset=utf8');


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
        echo "m180708_014443_create_metadata_tbl cannot be reverted.\n";

        return false;
    }
    */
}
