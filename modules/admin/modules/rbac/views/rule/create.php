<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="ui fluid container">
    <div class="ui grid">
        <div class="sixteen wide column">
            <div class="panel">
                <div class="panel-content" style="padding: 10px 15px;">
                    <div class="ui secondary">
                        <div class="ui compact menu">
                            <?= Html::a('<i class="reply icon"></i>返回', ['index'], ['class' => 'item']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $form = ActiveForm::begin([
        'id' => 'category',
        'enableClientScript' => false,
        'enableClientValidation' => false,
        'options' => [
            'class'=>'ui form'
        ],
        'fieldConfig' => [
            'template' => '<div class="field">{label}{input}{error}{hint}</div>'
        ]

    ]);
    ?>
    <div class="ui grid">
        <div class="ten wide column">
            <div class="panel" style="padding-top: 20px;">
                <div class="panel-content">
                    <?= $form->field($model, 'name',[
                            'options'=>['tag'=>false]
                    ])->textInput(['maxlength' => 64]) ?>

                    <?= $form->field($model, 'className',[
                            'options' => ['tag'=>false]
                    ])->textInput() ?>
                </div>
            </div>
        </div>
        <div class="six wide column">
            <div class="panel" style="padding-top: 20px;">
                <div class="panel-header">
                    <h4>规则创建说明：</h4>
                </div>
                <div class="panel-content">
                    <p>请指定规则类的类全名,如：app\components\rules\IsAuthor</p>

                </div>
            </div>
        </div>
    </div>
    <div class="ui grid">
        <div class="sixteen wide column">
            <div class="panel">
                <div class="panel-content" style="padding: 10px 15px;">
                    <?= Html::submitButton('点击提交',['class'=>'ui mini green button','id'=>'publish_btn'])?>
                    <?= Html::resetButton('重置',['class'=>'ui mini brown disabled button'])?>
                </div>
            </div>
        </div>
    </div>
    <?php
    ActiveForm::end();
    ?>
</div>

<?php
/*css*/
$cssStr = <<<CSS
    body {padding:20px;} .help-block{color:#DB2828!important}#articleform-tags label{border:1px solid #ddd;background-color: #efefef;padding: 5px 8px;vertical-align: center;border-radius: 3px;}
CSS;
$this->registerCss($cssStr);
?>



