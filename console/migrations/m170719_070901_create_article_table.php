<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170719_070901_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
//            id	primaryKey
            'name' => $this->string(50)->comment('文章名称'),
//name	varchar(50)	名称
            'intro' => $this->text()->comment('文章简介'),
//intro	text	简介
            'article_category_id' => $this->integer()->comment('文章分类id'),
//article_category_id	int()	文章分类id
            'sort' => $this->integer()->comment('排序'),
//sort	int(11)	排序
            'status' => $this->smallInteger(2)->comment('状态'),
//status	int(2)	状态(-1删除 0隐藏 1正常)
            'create_time' => $this->integer(11)->comment('创建时间')
//create_time	int(11)	创建时间

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
