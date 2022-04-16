<?php
namespace app\models;

//web后端
// use Yii;
// use common\helps\ArrayHelper;
// use app\models\User;
// use app\models\Worktime;


class Supplier extends \yii\db\ActiveRecord
{    
    public static function tableName()
    {
        return 'supplier';
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '供应商名',
            'code' => '供应商编号',
            't_status' => '状态',
        ];
    }
}  