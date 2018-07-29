<?php
use yii\helpers\Url;
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
                    <!--标题-->
                    <?php
                    echo $form->field($model, 'username',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '用户名'
                    ]);

                    echo $form->field($model, 'email',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '邮箱'
                    ]);

                    echo $form->field($model, 'password',['options'=>[
                        'tag' => false
                    ]])->passwordInput([
                        'placeholder' => '密码'
                    ]);

                    echo $form->field($model, 're_password',['options'=>[
                        'tag' => false
                    ]])->passwordInput([
                        'placeholder' => '重复密码'
                    ]);

                    /*echo $form->field($model, 'group',[
                        'options' => ['tag'=>false]
                    ])->radioList([
                        '0' => '普通用户',
                        '1' => '社区作者',
                        '2' => '后台管理'
                    ])->hint('请根据用户活动范围给予对应权限。',[
                        'class'=>'help-block',
                    ]);*/

                    if(!$model->isNewRecord){
                        echo $form->field($model, 'reset_image',['options'=>[
                            'tag' => false
                        ]])->checkbox()->label(false)->hint('选择后将重置用户头像。',[
                            'class'=>'help-block'
                        ]);
                    }
                    ?>


                </div>
            </div>
        </div>
        <div class="six wide column">
            <div class="panel" style="padding-top: 20px;">
                <div class="panel-header">
                    <h4>群组说明：</h4>
                </div>
                <div class="panel-content">
                    <dl>
                        <dt>后台管理：</dt>
                        <dd>对网站有绝对管理权，应赋予网站运营者，维护人员。</dd>
                    </dl>
                    <dl>
                        <dt>社区作者：</dt>
                        <dd>对于丰富网站内容，写作人员，应获取该权限。</dd>
                    </dl>
                    <dl>
                        <dt>普通用户：</dt>
                        <dd>指前台用户，可查阅，回复及评论文章。</dd>
                    </dl>

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

/*js*/
/*$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        
    });
JS;
$this->registerJs($jsStr);
*/?>



