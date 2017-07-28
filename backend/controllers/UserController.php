<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\PasswordForm;
use backend\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = User::find()->all();
        return $this->render('index',['models'=>$models]);
    }

    //用户添加
    public function actionAdd(){
        $model = new User();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager = \Yii::$app->authManager;
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
            $model->save();
            if(is_array($model->roles)){
                foreach ($model->roles as $roleName){
                    $role = $authManager->getRole($roleName);
                    if($role) $authManager->assign($role,$model->id);
                }
            }

            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    //用户修改
    public function actionEdit($id){
        $model = User::findOne(['id'=>$id]);
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id);
        $model->roles = ArrayHelper::map($roles,'name','name');
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
            $model->save();

            $authManager->revokeAll($id);
            if(is_array($model->roles)){
                foreach ($model->roles as $roleName){
                    $role = $authManager->getRole($roleName);
                    if($role) $authManager->assign($role,$model->id);
                }
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    //用户删除
    public function actionDelete($id){

       // $authManager = \Yii::$app->authManager;
        //$authManager->revokeAll($id);
        User::findOne(['id'=>$id])->delete();
        return $this->redirect(['user/index']);
    }

    //用户登录
    public function actionLogin(){
        $model = new LoginForm();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate() && $model->login()){
                //保存最后登录时间和ip
                $user = User::findOne(['username'=>$model->username]);
                $user->last_login_time = time();
                $user->last_login_ip = \Yii::$app->request->userIP;
                $user->save();
                //登录成功
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //退出
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }

    //修改密码
    public function actionPassword()
    {
        if(!\Yii::$app->user->isGuest){
//            var_dump(\Yii::$app->user->identity->id);exit;
            $model = new PasswordForm();
            $request = new Request();
            $user = User::findOne(['id'=>\Yii::$app->user->identity->id]);
            if($request->isPost){
                $model->load($request->post());
                //验证旧密码是否正确
                if(\Yii::$app->security->validatePassword($model->old_password,$user->password_hash)){
                    //旧密码正确的情况下,验证旧密码和新密码是否一致
                    if($model->old_password != $model->new_password){
                        //如果不一致，验证新密码和确认密码是否一致
                        if($model->new_password == $model->re_password){
                            $user->password_hash = \Yii::$app->security->generatePasswordHash($model->new_password);
                            $user->save();
                            \Yii::$app->session->setFlash('success','密码修改成功');
                            return $this->redirect(['user/index']);
                        }else{
                            $model->addError('re_password','新密码和确认密码不一致');
                        }
                    }else{
                        $model->addError('new_password','旧密码和新密码相同');
                    }
                }else{
                    $model->addError('old_password','旧密码不正确');
                }
            }
            return $this->render('password',['model'=>$model]);
        }else{
            return $this->redirect(['user/login']);
        }
    }




}
