<?= \yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-info']) ?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>文章分类</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->sort?></td>
            <td><?=backend\models\ArticleCategory::getIndexStatus($article->status)?></td>
            <td><?=$article->articleCategory ->name ?></td>
            <td><?=$article->create_time?></td>
            <td><?=\yii\bootstrap\Html::a('删除',['article/delete', 'id'=>$article->id],['class'=>'btn btn-danger']) ?>
                <?= \yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);