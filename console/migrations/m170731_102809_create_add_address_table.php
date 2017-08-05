<?php

use yii\db\Migration;

/**
 * Handles the creation of table `add_address`.
 */
class m170731_102809_create_add_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('address','province',$this->string());
            $this->addColumn('address','city',$this->string());
            $this->addColumn('address','user_id',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('add_address');
    }
}
