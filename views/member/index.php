<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

$defaultImage = Yii::$app->params['image'];
$this->params['hideSearch'] = true;
?>
<!--content-->
<section id="content">
    <div class="ui container">
        <div class="conts" style="overflow: hidden">
            <div class="ui column grid">
                <!--背景-->
                <div class="bg">
                    <h2 class="ui center aligned icon header" style="padding-top: 25px;">
                        <img src="<?= $user->image ? IMG_ROOT . $user->image : $defaultImage;?>" class="ui circular image">
                        <p style="color:white">
                            <?= $user->username?>
                            <small>
                                <?php
                                    $tem = ['『普通用户』','『社区作者』','『后台管理』'];
                                    echo $tem[$user->group];
                                ?>
                            </small>
                            <br>
                            <span style="font-size: 16px;">签名 ： <?= $user->profile? $user->profile->sign : '你好淡淡的 ～～!';?></span>
                        </p>
                    </h2>
                </div>
            <?php
            echo $this->render('_nav');  //导航
            ?>
            <div class="right-ares eleven wide column">
                <?php
                echo DetailView::widget([
                    'options' => [
                        'class' => 'ui very basic collapsing celled table',
                    ],

                    'model' => $user,
                    'attributes' => [
                        'username',
                        'email',
                        [
                            'attribute' => 'image',
                            'format' => 'raw',
                            'class' => 'ui small image',
                            'value' => function($model){
                                if(!empty($model->image)){
                                    return Yii::$app->params['imgPath']['imgUrl']. '/' . $model->image;
                                }else
                                    return Html::img(Yii::$app->params['image'],['class' => 'ui tiny image circular']);
                            }
                        ],
                        [
                            'label' => '真实姓名',
                            'value' => function($model){
                                return !empty($model->profile) ? $model->profile->realname : '';
                            }
                        ],
                        [
                            'label' => '签名',
                            'value' => function($model){
                                return !empty($model->profile) ? $model->profile->sign : '';
                            }
                        ],
                        [
                            'label' => '博客',
                            'value' => function($model){
                                return !empty($model->profile) ? $model->profile->blog : '';
                            }
                        ],
                        [
                            'attribute'=>'group',
                            'value' => function($model){
                                $arr = ['普通用户','社区作者','后台管理'];
                                return $arr[$model->group];
                            }
                        ],
                        [
                            'attribute'=>'count',
                            'visited' => false
                        ],
                        [
                            'label' => '位置',
                            'value' => function($model){
                                return !empty($model->profile) ? $model->profile->address : '';
                            }
                        ],

                        [
                            'attribute' => 'lasttime',
                            'visited' => $user->lasttime ? true : false,
                            'format' => 'datetime'
                        ],

                        'created_at:datetime',
                    ],

                ]);
                ?>
            </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->

