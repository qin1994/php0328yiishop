<?= \yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-info']) ?>
<table class="table table-bordered table-condensed">
<tr>
    <th>名称</th>
    <th>简介</th>
    <th>logo图片</th>
    <th>状态</th>
    <th>操作</th>
</tr>
<?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo?$brand->logo:'upload/default.png',['height'=>50])?></td>
        <td><?=$brand->status?></td>
        <td><?=\yii\bootstrap\Html::a('删除',['brand/delete', 'id'=>$brand->id],['class'=>'btn btn-danger']) ?>
                    <?= \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-info'])?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>