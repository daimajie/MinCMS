<?php
use yii\helpers\Html;
use yii\widgets\DetailView;


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
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">
                        <?php
                        echo DetailView::widget([
                            'options' => [
                                'class' => 'ui very basic collapsing celled table',
                            ],

                            'model' => $model,
                            'attributes' => [
                                'id',
                                'level',
                                'category',
                                'log_time',
                                'prefix',
                                'message',

                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");