<?php
/* @var $this yii\web\View */
?>
<h1>权限/列表</h1>
<?= \yii\bootstrap\Html::a('添加',['rbac/add-permission'],['class'=>'btn btn-info']) ?>
<table class="table table-bordered table-condensed table-hover">
    <tr>
        <th>权限名(路由)</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['edit-permission', 'name'=>$model->name],['class'=>'btn btn-danger']) ?>
                <?= \yii\bootstrap\Html::a('删除',['delete-permission','name'=>$model->name],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

