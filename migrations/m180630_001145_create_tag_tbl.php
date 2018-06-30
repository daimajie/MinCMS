<?php

use yii\db\Migration;

/**
 * Class m180630_001145_create_tag_tbl
 */
class m180630_001145_create_tag_tbl extends Migration
{
    const TBL_NAME = '{{%tag}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME, [
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'name' => $this->string(12)->notNull()->defaultValue('')->comment('标签名'),
            'topic_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属话题'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建者'),
        ], 'engine=innodb default charset=utf8');

        //外键
        /*$this->addForeignKey(
            'fk-tag-topic_id',
            self::TBL_NAME,
            'topic_id',
            '{{%topic}}',
            'id',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-tag-user_id',
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
        echo "m180630_001145_create_tag_tbl cannot be reverted.\n";

        return false;
    }
    */
}
