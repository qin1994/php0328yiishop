<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_022947_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('菜单名称'),
            'url'=>$this->string(100)->comment('路由/地址'),
            'parent_id'=>$this->integer()->comment('上级分类ID'),
            'sort'=>$this->integer()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
