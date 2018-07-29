<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

    <div class="ui fluid container">
        <?php
        if(!Yii::$app->request->isAjax):
            ?>
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
        endif;
        ?>

        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">

                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'category',
                            'enableAjaxValidation' => true,
                            'options' => [
                                'class'=>'ui form'
                            ],
                            'fieldConfig' => [
                                'template' => '<div class="field">{label}{input}{error}</div>'
                            ]
                        ]); ?>
                        <?php if($model->type === 0):?>
                        <div class="field">
                            <label>评论的文章</label>
                            <?= $model->article->title?>
                        </div>
                        <?php else:?>
                            <div class="field">
                                <label>回复的评论</label>
                                <?= $model->comment->content?>
                            </div>
                        <?php endif;?>

                        <div class="field">
                            <label>用户</label>
                            <?= $model->user->username?>
                        </div>

                        <?=
                        $form->field($model, 'content',['options'=>[
                            'tag' => false
                        ]])->textarea(['rows' => 5,'class'=>false]);
                        ?>




                        <?= Html::submitButton('点击提交',['class'=>'ui green button'])?>
                        <?php
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        
    })
JS;
$this->registerJs($jsStr);
?>