<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    public static function getStatusOptions($hidden_del=true){
        $options = [ -1=>'删除',0=>'隐藏',1=>'正常'

        ];
        if($hidden_del){
            unset($options['-1']);
        }
        return $options;
    }

    public static function getIndexStatus($options){
        $status = [ -1=>'删除',0=>'隐藏',1=>'正常'];

        return $status[$options];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['name','intro','sort','status'],'required','message'=>'{attribute}不能为空'],
            //['imgFile','file','extensions'=>['jpg','png','gif']],
            //['code','captcha','captchaAction'=>'admin/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
