<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $area
 * @property string $addresses
 * @property string $tel
 */
class Address extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['name','province','city','area','addresses','tel'],'required','message'=>'{attribute不能为空}'],
            [['area'], 'string', 'max' => 200],
            [['addresses'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
            [['status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '地区',
            'addresses' => '详细地址',
            'tel' => '电话',
            'status' => '默认收货地址',
        ];
    }
}
