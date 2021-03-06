<?php
namespace frontend\models;

use app\models\Member;
use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password_hash;
    public $auto;
    public function rules()
    {
        return [
            [['username','password_hash'],'required','message'=>'{attribute}不能为空'],
            [['auto'],'safe']

        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'auto'=>'自动登录',
        ];
    }

    public function login(){
        //通过用户名查找用户
        $member_log = Member::findOne(['username'=>$this->username]);
        if($member_log){
            if(\Yii::$app->security->validatePassword($this->password_hash,$member_log->password_hash)){
               \Yii::$app->user->login($member_log,$this->auto?3600*24:0);
                return true;
            }else{
                //密码错误.提示错误信息
                $this->addError('password','密码错误');
            }
        }else{
            //用户不存在,提示 用户不存在 错误信息
            $this->addError('username','用户名不存在');
        }
        return false;
    }
}