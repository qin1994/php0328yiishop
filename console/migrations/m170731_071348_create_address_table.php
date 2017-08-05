<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170731_071348_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('收货人'),
            'area'=>$this->string(200)->comment('地区'),
            'addresses'=>$this->string(255)->comment('详细地址'),
            'tel'=>$this->string(11)->comment('电话'),
            $this->addColumn('address','province',$this->string()),
            $this->addColumn('address','city',$this->string()),
            $this->addColumn('address','user_id',$this->integer())
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
