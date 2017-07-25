<?php

use yii\db\Migration;

/**
 * Handles the creation of table `add_user`.
 */
class m170724_063037_create_add_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user','last_login_time',$this->integer());
        $this->addColumn('user','last_login_ip',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('add_user');
    }
}
