<?php
namespace app\controllers;

use app\common\helps\Excel;
use Yii;

use app\models\Supplier;
use app\models\SupplierSearch;
use yii\helpers\Json;
use app\common\helps\Helps;

class SupplierController extends SiteController
{
    public function actionIndex(){
        
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $exportCloumns = $searchModel->attributeLabels();
        $sign=Yii::$app->request->get('sign');
        return $this->render('index',
        [
            "dataProvider"=>$dataProvider,
            "searchModel"=>$searchModel,
            "exportCloumns"=>$exportCloumns,
            "sign"=>$sign,
        ]);
    }
    /*
     * 操作链接使用的单个删除
     */
    public function actionDelete(){
        $id=Yii::$app->request->get('id');
        $model = Supplier::findOne($id);        
        $model->delete();
        return $this->redirect(['supplier/index']);
    }
    /*
     * 操作js使用的单个删除
     */
    public function actionDelete_js($id){
        try{
            $model = Supplier::findOne($id);        
            $model->delete();
            echo Json::encode(['done'=>true]);
        } catch (\Exception $e) {
            echo Json::encode(['done'=>false,'error'=>$e->getMessage()]);
        }
    }
    /*
     * 多选删除js
     */
    public function actionDelete_all(){
        try{
            $ids=Yii::$app->request->post('ids');
            $ids=explode(',',$ids);
            //数组直接查询
            $lists = Supplier::find()->where(['in','id',$ids])->all();     
            foreach($lists as $list){
                $list->delete();
            }
            echo Json::encode(['done'=>true]);
        } catch (\Exception $e) {
            echo Json::encode(['done'=>false,'error'=>$e->getMessage()]);
        }
    }

    /**
     * 导出记录
     */
    public function actionExport() {
        try{
            $ids = explode(',', Yii::$app->request->post('ids'));
            $fields = explode(',', Yii::$app->request->post('fields'));
            $headers = explode(',', Yii::$app->request->post('headers'));

            $data = Supplier::find()->select($fields)->where(['in','id',$ids])->asArray()->all();
            $headers = Helps::get_header_columns($headers);
            Excel::output($headers, $data);

        } catch (\Exception $e) {
            echo Json::encode(['done'=>false,'error'=>$e->getMessage()]);
        }
    }
 
}