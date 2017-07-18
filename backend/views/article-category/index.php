<?= \yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-info']) ?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articleCategorys as $articleCategory):?>
        <tr>
            <td><?=$articleCategory->name?></td>
            <td><?=$articleCategory->intro?></td>
            <td><?=$articleCategory->status?></td>
            <td><?=\yii\bootstrap\Html::a('删除',['article-category/delete', 'id'=>$articleCategory->id],['class'=>'btn btn-danger']) ?>
                <?= \yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$articleCategory->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>