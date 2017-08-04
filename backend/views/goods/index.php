<?= \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info']) ?>
<?php
    $form = \yii\bootstrap\ActiveForm::begin([
            'layout'=>'inline',
            'method'=>'get',
            'action'=>['goods/index']
    ]);
    echo $form->field($model,'name')->textInput(['placeholder'=>'商品名字']);
    echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-default']);

    \yii\bootstrap\ActiveForm::end();
?>
    <table class="table table-bordered table-condensed">
        <tr>
            <th>id</th>
            <th>货号</th>
            <th>名称</th>
            <th>价格</th>
            <th>库存</th>
            <th>logo</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php foreach ($goods as $good):?>
            <tr>
                <td><?=$good->id?></td>
                <td><?=$good->sn?></td>
                <td><?=$good->name?></td>
                <td><?=$good->shop_price?></td>
                <td><?=$good->stock?></td>
                <td><?=\yii\bootstrap\Html::img($good->logo?$good->logo:'upload/default.png',['height'=>50])?></td>

                <td><?=backend\models\Brand::getIndexStatus($good->status)?></td>
                <td> <?=\yii\bootstrap\Html::a('相册',['gallery','id'=>$good->id],['class'=>'btn btn-default'])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods/delete', 'id'=>$good->id],['class'=>'btn btn-danger']) ?>
                    <?= \yii\bootstrap\Html::a('修改',['goods/edit','id'=>$good->id],['class'=>'btn btn-info'])?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);