<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\web\HttpException;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = Menu::find()->all();
        return $this->render('index',['models'=>$models]);
    }
    //菜单添加
    public function actionAdd(){
        $model = new Menu();
        $models = Menu::find()->where(['<=','parent_id','1'])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model,'models'=>$models]);
    }

    //菜单修改
    public function actionEdit($id){
        $model = Menu::findOne(['id'=>$id]);
        $models = Menu::find()->where(['<=','parent_id','1'])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断顶级菜单不能修改到子菜单
           /* if(Menu::findOne(['id'=>$model->parent_id])){
                throw new HttpException(404,'顶级菜单不能修改');
            }*/
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model,'models'=>$models]);
    }
    //菜单删除
    public function actionDelete($id){
        $model = Menu::findOne(['id'=>$id]);
        $qq = Menu::findOne(['parent_id'=>$id]);
        //判断如果该菜单下有子菜单,则不能删除
        if(empty($qq)){
            $model->delete();
       }else{
            //错误提示信息
            \Yii::$app->session->setFlash('danger','该菜单下面有子菜单,不能删除');
       }
        return $this->redirect(['menu/index']);
    }
}
