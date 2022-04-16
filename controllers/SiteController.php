<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Supplier;
use yii\grid\CheckboxColumn;
use yii\helpers\Url;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTest(){

        $dataProvider = new ActiveDataProvider([
            'query' => Supplier::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'showFooter' => true,  //设置显示最下面的footer
            'id' => 'grid',
            'caption'=>"会员列表",
            'tableOptions' => ['style' => 'width:100%; broder:1px; font-size:20px; text-align:right;'],
            'columns' => [
              [
                'class'=>CheckboxColumn::className(),
                'name'=>'id',
                'headerOptions' => ['style' => 'width:100;'],
                'footer' => '<button href="#" class="btn btn-default btn-xs btn-delete" url="'. Url::toRoute('admin/delete') .'">删除</button>',
                'footerOptions' => ['colspan' => 5],
                'options' => ['style' => 'font-color:red;']
              ],
              ['attribute' => 'id', 'footerOptions' => ['class'=>'hide']], //其他列每个都要增加footerOptions项，设置class为hide，到达隐藏效果；
              ['attribute' => 'name', 'footerOptions' => ['class'=>'hide']],
              [
                'attribute' => 'status',
                'value' => function($model){
                  if ($model->t_status == Supplier::STATUS_ACTIVE){
                    return '启用';
                  }
                  return '禁用';
                },
                'footerOptions' => ['class'=>'hide']
              ],
              ['class' => 'yii\grid\ActionColumn', 'header' => '管理操作', 'footerOptions' => ['class'=>'hide']],
            ],
           'layout' => "{items}\n{pager}"
        ]);
exit;
        // var ids = $("#grid").yiiGridView("getSelectedRows");

    }
}
