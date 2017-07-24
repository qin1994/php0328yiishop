<?php
namespace backend\models;

use yii\base\Model;

class GoodsSearchForm extends Model{
    public $name;

    public function rules()
    {
        return [
           [['name'],'safe'],
        ];
    }
}