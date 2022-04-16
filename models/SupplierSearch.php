<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SupplierSearch extends Supplier
{
    public function rules()
    {
        // 只有在 rules() 函数中声明的字段才可以搜索,不声明不显示搜索框
        return [
           [['id','name','code','t_status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // 旁路在父类中实现的 scenarios() 函数
        return Model::scenarios();
    }

    public function search($params){
        $query = Supplier::find();
        
        if(!Yii::$app->request->get('sort')){
            $query->orderBy('id desc');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                    'pageSize' => 15,
                ],
        ]);

        // 从参数的数据中加载过滤条件，并验证
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        // 增加过滤条件来调整查询对象
        $query->andFilterWhere(['=', 'id', $this->id])
              ->andFilterWhere(['=', 'name', $this->name])
              ->andFilterWhere(['=', 'code', $this->code]);
        if($this->t_status != 'all'){
            $query->andFilterWhere(['=', 't_status', $this->t_status]);
        }

        return $dataProvider;
    }
}