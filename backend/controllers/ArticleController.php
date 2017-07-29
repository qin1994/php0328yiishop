<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Request;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;


class ArticleController extends \yii\web\Controller
{
    //展示文章列表
    public function actionIndex()
    {
        //分页 总条数 每页显示条数 当前第几页
        $query = Article::find()->where('status>=0');//Brand::find()->where('status>=0');
        //总条数
        $total = $query->count();
        //var_dump($total);exit;
        //每页显示条数 2
        $perPage = 2;

        //分页工具类
        $pager = new Pagination([
            'totalCount' => $total,
            'defaultPageSize' => $perPage
        ]);

        $articles = $query->orderBy('sort')->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['articles' => $articles, 'pager' => $pager]);
        //$articles = Article::find()->all();
       // return $this->render('index',['articles'=>$articles]);
    }

    //添加文章
    public function actionAdd()
    {
        $model = new Article();
        $data = ArticleCategory::find()->all();
        $detail = new ArticleDetail();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $detail->load($request->post());
            if ($model->validate() && $detail->validate()) {
                $model->create_time = time();
                $model->save();
                $detail->article_id = $model->id;
                $detail->save();
                return $this->redirect(['article/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add', ['model' => $model, 'detail' => $detail, 'data' => $data]);
    }

    //文章修改
    public function actionEdit($id)
    {
        $model = Article::findOne(['id' => $id]);
        //$model = new Article();
        $data = ArticleCategory::find()->all();
        $detail = ArticleDetail::findOne(['article_id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $detail->load($request->post());
            if ($model->validate() && $detail->validate()) {
                $model->create_time = time();
                $model->save();
                $detail->article_id = $model->id;
                $detail->save();
                return $this->redirect(['article/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add', ['model' => $model, 'detail' => $detail, 'data' => $data]);
    }

    //文章删除(逻辑删除)
    public function actionDelete($id)
    {
        $model = Article::findOne(['id' => $id]);
        $model->status = -1;
        $model->save();
        return $this->redirect(['article/index']);
    }

    //七牛云文件上传
    public function actions()
    {
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
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /* 'format' => function (UploadAction $action) {
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
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {
                },
                'beforeSave' => function (UploadAction $action) {
                },
                'afterSave' => function (UploadAction $action) {
                    //$action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $url;
                },
            ],
        ];
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





