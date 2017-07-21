<?= \yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info']) ?>
    <table class="table table-bordered table-condensed">
        <tr>
            <th>id</th>
            <th>名称</th>
            <th>简介</th>
            <th>层级</th>
            <th>操作</th>
        </tr>
        <?php foreach ($goodsCategorys as $goodsCategory):?>
            <tr>
                <td><?=$goodsCategory->id?></td>
                <td><?=$goodsCategory->name?></td>
                <td><?=$goodsCategory->intro?></td>
                <td><?=$goodsCategory->depth?></td>
                <td><?=\yii\bootstrap\Html::a('删除',['goods-category/delete', 'id'=>$goodsCategory->id],['class'=>'btn btn-danger']) ?>
                    <?= \yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$goodsCategory->id],['class'=>'btn btn-info'])?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);