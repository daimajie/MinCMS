<?php

use yii\db\Migration;

/**
 * Class m180717_080541_create_profile_tbl
 */
class m180717_080541_create_profile_tbl extends Migration
{
    const TBL_NAME = '{{%profile}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME, [
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'realname' => $this->string(18)->notNull()->defaultValue('')->comment('真实姓名'),
            'address' => $this->string(32)->notNull()->defaultValue('')->comment('位置'),
            'sign' => $this->string(225)->notNull()->defaultValue('')->comment('签名'),
            'blog' => $this->string(125)->notNull()->defaultValue('')->comment('博客'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('用户'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),

        ], 'engine=innodb default charset=utf8');

        $this->createIndex(
            'idx-profile-user_id',
            self::TBL_NAME,
            'user_id'
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
        echo "m180717_080541_create_profile_tbl cannot be reverted.\n";

        return false;
    }
    */
}
