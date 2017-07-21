<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($detail,'content')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($data,'id','name'));
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Article::getStatusOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();