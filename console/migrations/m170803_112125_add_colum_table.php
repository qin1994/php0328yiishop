<?php

use yii\db\Migration;

class m170803_112125_add_colum_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('address','member_id','int');

    }

    public function safeDown()
    {
        echo "m170803_112125_add_colum_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170803_112125_add_colum_table cannot be reverted.\n";

        return false;
    }
    */
}
