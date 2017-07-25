<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
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
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
            $model->save();
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    //用户修改
    public function actionEdit($id){
        $model = User::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
            $model->save();
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    //用户删除
    public function actionDelete($id){
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
}
