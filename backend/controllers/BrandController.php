<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    //添加品牌
    public function actionAdd(){
    $model = new Brand();
    $request = new Request();
    if ($request->isPost){
        $model->load($request->post());
        //实例化文件上传对象
        $model->imgFile = UploadedFile::getInstance($model,'imgFile');
        //验证数据
        if($model->validate()){
            //处理图片
            //文件上传
            if($model->imgFile){
                $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                if(!is_dir($d)){
                    mkdir($d);
                }
                $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;

                //创建文件夹
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                $model->logo = $fileName;
            }
            $model->save(false);
            return $this->redirect(['brand/index']);
        }else{
            //验证失败  打印错误信息
            var_dump($model->getErrors());exit;
        }
    }
    return $this->render('add',['model'=>$model]);
}
    //展示首页商品列表
    public function actionIndex()
    {
        //分页 总条数 每页显示条数 当前第几页
        $query = Brand::find()->where('status>=0');
        //总条数
        $total = $query->count();
        //var_dump($total);exit;
        //每页显示条数 2
        $perPage = 2;

        //分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);

        //LIMIT 0,3   ==> limit(3)->offset(0)
        $brands = $query->orderBy('sort')->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
    }

    //修改商品
    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            //实例化文件上传对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                //处理图片
                //文件上传
                if($model->imgFile){
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;

                    //创建文件夹
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $model->logo = $fileName;
                }
                $model->save(false);
                return $this->redirect(['brand/index']);
            }else{
                //验证失败  打印错误信息
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
      $model=  Brand::findOne(['id'=>$id]);
        $model->status = -1;
        $model->save();
        return $this->redirect(['brand/index']);
    }
}
