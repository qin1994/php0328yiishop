<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{

    public $imgFile;

    public static function getStatusOptions($hidden_del=true){
       $options = [ -1=>'删除',0=>'隐藏',1=>'正常'

    ];
       if($hidden_del){
           unset($options['-1']);
       }
       return $options;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['name','intro','sort'],'required','message'=>'{attribute}不能为空'],
            ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false],
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
            'logo' => 'logo图片',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
