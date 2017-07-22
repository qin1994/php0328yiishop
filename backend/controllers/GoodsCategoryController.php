<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsCategoryQuery;
use yii\data\Pagination;
use yii\web\HttpException;

class GoodsCategoryController extends \yii\web\Controller
{

    //添加商品分类（ztree选择上级分类id）
    public function actionAdd()
    {
        $model = new GoodsCategory(['parent_id'=>0]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类

                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);

        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }

    public function actionIndex()
    {
        //分页 总条数 每页显示条数 当前第几页
        $query = GoodsCategory::find();
        //总条数
        $total = $query->count();
        //var_dump($total);exit;
        //每页显示条数 2
        $perPage = 4;

        //分页工具类
        $pager = new Pagination([
            'totalCount' => $total,
            'defaultPageSize' => $perPage
        ]);

        $goodsCategorys = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['goodsCategorys'=>$goodsCategorys,'pager' => $pager]);
    }
        //修改商品分类
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类

                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类

                if($model->oldAttributes['parent_id'] == 0){
                    $model->save();

                }else{
                    $model->makeRoot();
                }

            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);

        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    //删除商品分类
    public function actionDelete($id){

        $model=GoodsCategory::findOne(['id'=>$id]);
        $aa = GoodsCategory::findOne(['parent_id'=>$id]);
        if(empty($aa)){
            $model->delete();
        }else{
            //错误提示信息
            \Yii::$app->session->setFlash('danger','分类下面有子类,不能删除');
        }
        return $this->redirect(['goods-category/index']);
    }
}
