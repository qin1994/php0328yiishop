<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
    //添加品牌
    public function actionAdd(){
    $model = new Brand();
    $request = new Request();
    if ($request->isPost){
        $model->load($request->post());
//        //实例化文件上传对象
//        $model->imgFile = UploadedFile::getInstance($model,'imgFile');
        //验证数据
        if($model->validate()){
            //处理图片
            //文件上传
           /* if($model->imgFile){
                $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                if(!is_dir($d)){
                    mkdir($d);
                }
                $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;

                //创建文件夹
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                $model->logo = $fileName;
            }*/
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



    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']  = $url;
                },
            ],
        ];
    }
}
