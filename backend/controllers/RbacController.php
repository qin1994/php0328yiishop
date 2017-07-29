<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
//use Behat\Gherkin\Loader\YamlFileLoader;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    /******************************************权限操作开始***********************************************/
    public function actionIndexPermission()
    {
        //获取所有的权限
//
        $authManager = \Yii::$app->authManager;
        $models = $authManager->getPermissions();
        return $this->render('index-permission',['models'=>$models]);
    }

    //添加权限
    public function actionAddPermission(){
        $model = new PermissionForm();
        $model->scenario = PermissionForm::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager = \Yii::$app->authManager;
            //创建权限
            $permission = $authManager->createPermission($model->name);
            $permission->description = $model->description;
            //保存数据
            $authManager->add($permission);

            \Yii::$app->session->setFlash('success','权限添加成功');
            return $this->redirect(['index-permission']);
        }

        return $this->render('add-permission',['model'=>$model]);
    }

    //修改权限
    public function actionEditPermission($name){
        //检查权限是否存在
        $authManage = \Yii::$app->authManager;
        $permission = $authManage->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }

        $model = new PermissionForm();
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //将表单数据赋值给权限
                $permission->name = $model->name;
                $permission->description = $model->description;
                //更新权限
                $authManage->update($name,$permission);

                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect(['index-permission']);
            }
        }else{
            //回显权限数据到表单
            $model->name = $permission->name;
            $model->description = $permission->description;
        }

        return $this->render('add-permission',['model'=>$model]);
    }

    //删除权限
    public function actionDeletePermission($name){
        $authManage = \Yii::$app->authManager;
        $permission = $authManage->getPermission($name);

        $authManage->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['index-permission']);
    }
    /******************************************权限操作结束***********************************************/

    /******************************************角色操作开始***********************************************/

    //角色添加
    public function actionAddRole(){
        $model = new RoleForm();
        $model->scenario = RoleForm::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //创建角色
            $authManager = \Yii::$app->authManager;
            $role = $authManager->createRole($model->name);
            $role->description = $model->description;
            //保存角色
            $authManager->add($role);
            if(is_array($model->permissions)) {
                foreach ($model->permissions as $permissionName) {
                    $permission = $authManager->getPermission($permissionName);
                    if ($permission) $authManager->addChild($role, $permission);
                }
            }
            \Yii::$app->session->setFlash('success','添加角色成功');
            return $this->redirect(['index-role']);
        }

        return $this->render('add-role',['model'=>$model]);
    }

    //角色列表
    public function actionIndexRole(){
//        $authManager = \Yii::$app->authManager;
//        $models = $authManager->getPermissions();
//        return $this->render('index-permission',['models'=>$models]);
        $authManager = \Yii::$app->authManager;
        $models = $authManager->getRoles();
        return $this->render('index-role',['models'=>$models]);
    }

    //角色修改
    public function actionEditRole($name){
        //检查角色是否存在
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if($role == null){
            throw new NotFoundHttpException('角色不存在');
        }
        $model = new RoleForm();
        if(\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                //回显的时候必须先取消关联,在继续关联
                //取消关联
                $authManager->removeChildren($role);
                //重新关联
                if (is_array($model->permissions)) {
                    foreach ($model->permissions as $permissionName) {
                        $permission = $authManager->getPermission($permissionName);
                        if ($permission) $authManager->addChild($role, $permission);
                    }
                }
                \Yii::$app->session->setFlash('success', '修改角色成功');
                return $this->redirect(['index-role']);
            }
            //回显权限
        }else{
            $permissions = $authManager->getPermissionsByRole($name);
            $model->name = $role->name;
            $model->description = $role->description;
            $model->permissions = ArrayHelper::map($permissions,'name','name');
        }

        return $this->render('add-role',['model'=>$model]);
    }
    public function actionDeleteRole($name){
        $authManage = \Yii::$app->authManager;
        //找到角色名,通过角色名删除
        $role = $authManage->getRole($name);

        $authManage->remove($role);
        \Yii::$app->session->setFlash('success','角色删除成功');
        return $this->redirect(['index-role']);
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}
