<?php
/* @var $this yii\web\View */
?>
<h1>菜单/列表</h1>
<?= \yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-info']) ?>
<table class="table table-bordered table-hover table-condensed">
    <tr>
        <th>id</th>
        <th>菜单名称</th>
        <th>路由/地址</th>
        <th>上级菜单</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->url?></td>
        <td><?=$model->parent_id?></td>
        <td><?=$model->sort?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['edit', 'id'=>$model->id],['class'=>'btn btn-danger']) ?>
            <?= \yii\bootstrap\Html::a('删除',['delete','id'=>$model->id],['class'=>'btn btn-info'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>


