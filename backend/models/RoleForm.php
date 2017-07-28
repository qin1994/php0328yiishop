<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];
    const SCENARIO_ADD = 'add';

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            ['name','validateName','on'=>self::SCENARIO_ADD]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'角色名字',
            'description'=>'描述',
            'permissions'=>'权限'
        ];
    }

    public function validateName(){
        $authManager = \Yii::$app->authManager;
        if($authManager->getPermission($this->name)){
            $this->addError('name','角色已存在');
        }
    }
}