<?php

use yii\db\Migration;

/**
 * Class m180630_014449_create_article_tag_tbl
 */
class m180630_014449_create_article_tag_tbl extends Migration
{
    const TBL_NAME = '{{%article_tag}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'article_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章ID'),
            'tag_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('标签ID')
        ],'engine=innodb default charset=utf8');


        /*$this->addForeignKey(
            'fk-article_tag-article_id',
            self::TBL_NAME,
            'article_id',
            '{{%article}}',
            'id',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-article_tag-tag_id',
            self::TBL_NAME,
            'tag_id',
            '{{%tag}}',
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
        echo "m180630_014449_create_article_tag_tbl cannot be reverted.\n";

        return false;
    }
    */
}
