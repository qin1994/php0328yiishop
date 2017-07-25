<?= \yii\bootstrap\Html::a('添加',['user/add'],['class'=>'btn btn-info']) ?>
    <table class="table table-bordered table-condensed table-hover">
        <tr>
            <th>id</th>
            <th>用户名</th>
            <th>邮箱</th>
            <th>最后登录时间</th>
            <th>最后登录IP</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->username?></td>
                <td><?=$model->email?></td>
                <td><?=date('Y-m-d',$model->last_login_time)?></td>
                <td><?=$model->last_login_ip?></td>
                <td><?=\yii\bootstrap\Html::a('删除',['user/delete', 'id'=>$model->id],['class'=>'btn btn-danger']) ?>
                    <?= \yii\bootstrap\Html::a('修改',['user/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
//分页工具条
//echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);