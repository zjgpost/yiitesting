<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\Supplier;
use app\common\helps\Helps;
// use yii\bootstrap\ActiveForm;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">供应商列表</h3>
                <button type="button" id="export_button" class="box">导出数据</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <?php
                    echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            //默认layout的表格三部分可不写：几条简介，表格，分页；可以去掉任意部分 
                            'layout' => "{summary}\n{items}\n{pager}" ,
                            //没有数据时候显示的内容和html样式
                            'emptyText'=>'当前没有内容',
                            'emptyTextOptions'=>['style'=>'color:red;font-weight:bold'],
                            //给所有的行属性增加id，或class，方便后面选择后整行改变颜色
                            'rowOptions'=>function($model){
                                return ['id'=>"tr-".$model->id];
                            },  
                            //显示底部（就是多了一栏），默认是关闭的
                            'showFooter'=>true,
                            'columns' => [
                                [
                                    'class' => 'yii\grid\CheckboxColumn',
                                    'footerOptions'=>['colspan'=>5],
                                    'footer'=>'<a href="javascript:;" class="_delete_all" data-url="'.Yii::$app->urlManager->createUrl(['/supplier/delete_all']).'">删除全部</a>',
                                    
                                ],
                                [  
                                    'header'=>'<a href="www.baidu.com">ID</a>',
                                    'label'=>'ID',
                                    'attribute'=>'id',
                                    'value' => function ($data) {
                                        return $data->id;//返回搜索值
                                    },
                                    'footerOptions'=>['style'=>'display:none'],        
                                ],
                                [  
                                    'header'=>'<a href="www.baidu.com">供应商名</a>',
                                    'label'=>'供应商名',
                                    'attribute'=>'name',
                                    'value' => function ($data) {
                                        $arr=ArrayHelper::map(Supplier::find()->all(),'id','name');
                                        return $arr[$data->id];//返回搜索值
                                    },
                                    'footerOptions'=>['style'=>'display:none'],        
                                ],
                                [  
                                    'header'=>'<a href="www.baidu.com">供应编码</a>',
                                    'label'=>'供应商编码',
                                    'attribute'=>'code',
                                    'value' => function ($data) {
                                        $arr=ArrayHelper::map(Supplier::find()->all(),'id','code');
                                        return $arr[$data->id];//返回搜索值
                                    },
                                    'footerOptions'=>['style'=>'display:none'],        
                                ],                             
                                [
                                    'attribute'=>'t_status',
                                    'value' => function ($data) {
                                        $arr=Helps::get_attend_status_label();
                                        return $arr[$data->t_status];
                                    },
                                    'filter' => Helps::get_attend_status(),
                                    'format' => 'raw', //显示label样式，否则显示html代码
                                    'footerOptions'=>['style'=>'display:none'],        
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    //操作
                                    "header" => "操作",
                                    'headerOptions' => ['width' => '100'],
                                    'template'=>'{view} {update} {delete}',
                                    //自定义功能按钮
                                    "buttons" =>[],
                                    'footerOptions'=>['style'=>'display:none'],        
                                ],
                            ]
                     ]);
                ?>
                <!-- /.box -->
            </div>
        </div>
        <div id="light" class="white_content">导出设置>></br>
            <?php foreach($exportCloumns as $field => $lable) {?>
            <input type="checkbox" title="<?=$lable?>" name="cloumns[]" value="<?=$field?>" class="field">
            <span class="field_lable"><?=$lable;}?></span>
            <input id="select_ids" type="hidden" name="datas" value="">
            <span >
                <button id="export" class="button" type="button" onclick = "">确认导出</button>
                <button class="button" type="button" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">关闭窗口</button>
            </span>
        </div>
        <div id="fade" class="black_overlay"></div>
    </div>
</div>
<style>
        .select_bg {
            background: BCC8D0;
        }
        .black_overlay{
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            background-color: black;
            z-index:1001;
            -moz-opacity: 0.8;
            opacity:.80;
            filter: alpha(opacity=88);
        }
        .white_content {
            display: none;
            position: absolute;
            top: 25%;
            left: 25%;
            width: 35%;
            height: 45%;
            padding: 20px;
            border: 10px solid orange;
            background-color: white;
            z-index:1002;
            overflow: auto;
        }
        .button {
            display:inline;
            margin-top: 150px;
            margin-left: 100px;
            text-align: center;
        }
        .field {
            display:inline;
            margin-left: 15px;
        }
    </style>
    <?=Html::jsFile('@vendor/jquery/dist/jquery.js'); ?>
<script>
        $("._delete").click(function() {
            var url = $(this).attr('data-url');
            console.log(url);
            $.getJSON(url, {}, function(d) {
                if (d.done == true) {
                    alert('删除成功');
                    window.location.href = "<?=Url::to(['supplier/index'])?>";
                } else {
                    alert(d.error);
                }
            });
        });
        $("._delete_all").click(function() {
            var many_check = $("input[name='selection[]']:checked");
            var ids = "";
            $(many_check).each(function() {
                ids += this.value + ',';
            });
            //去掉最后一个逗号
            if (ids.length > 0) {
                ids = ids.substr(0, ids.length - 1);
            } else {
                alert('请选择至少一条记录！');
                return false;
            }
            var url = $(this).attr('data-url');
            $.post(url, {
                ids
            }, function(d) {
                console.log(d);
                if (d.done == true) {
                    console.log(1);
                    alert('删除成功！');
                    window.location.href = "<?=Url::to(['supplier/index'])?>";
                } else {
                    alert(d.error);
                }
            }, 'json');
        });

        $("#export_button").click(function() {
            var selections = [];
            var many_check = $("input[name='selection[]']:checked");
            if (many_check.length > 0) {
                many_check.each(function(){
                    selections.push($(this).val());
                });
                var selectionIds = selections.join(',');
                $('#select_ids').val(selectionIds);
                $('#light').show();
            }else {
                alert('请检查是否选中数据!');
            }
        });

        $('#export').click(function(){
            var selectionids = [];
            var cloumns = [];
            var ids = $('#select_ids').val();
            var fields = $("input[name='cloumns[]']:checked");
            var texts = $("input[name='cloumns[]']:checked").next().attr("class", 'field_label').text();
            var url = "<?=Url::to(['supplier/export'])?>";
            if(fields.length > 0){
                fields.each(function(){
                    selectionids.push($(this).val());
                    cloumns.push($(this).attr("title"));
                });
                var fields = selectionids.join(',');
                var headers = cloumns.join(',');
                console.log(headers);
                $.post(url, {
                ids,
                fields,
                headers
            }, function(d) {
                console.log(d);
                if (d.done == true) {
                    console.log(1);
                    alert('删除成功！');
                } else {
                    alert(d.error);
                }
            }, 'json');
            }else{
                alert('请选择导出字段!');
            }
        });
    </script>