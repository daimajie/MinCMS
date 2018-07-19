<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\helper\Helper;
use yii\widgets\LinkPager;

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
                    <div class="ui hidden divider"></div>
                    <!--center-->
                    <div class="ui items">
                        <?php
                        if(!empty($articles)):
                        foreach ($articles as $item):
                        ?>
                        <div class="item">
                            <div class="content">
                                <a href="<?= Url::to(['article/index','id'=>$item['article']['id']])?>" class="header"><?= Html::encode($item['article']['title'])?></a>
                                <div class="description">
                                    <p><?= Helper::truncate_utf8_string(Html::encode($item['article']['brief']),55)?></p>
                                </div>
                                <div class="extra">
                                    <span><?= $info['info']?>: <?= Yii::$app->formatter->asRelativeTime($item['created_at'])?></span>
                                    <a class="item cancel-btn" data-id="<?= $item['id']?>">
                                        <div class="ui teal horizontal label"><?= $info['text']?></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                        endforeach;
                        else:
                            echo '暂无数据';
                        endif;
                        ?>
                    </div>
                    <!--/center-->
                    <div class="ui hidden divider"></div>
                    <!--pager-->
                    <div id="pager">
                        <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'options' => ['tag'=>'div','class'=>'ui pagination menu tiny'],
                            'linkContainerOptions' => ['tag'=>'dev'],
                            'linkOptions' => ['class' => 'item'],
                            'nextPageLabel' => '下一页',
                            'prevPageLabel' => '上一页',
                            'disabledPageCssClass' => 'item',
                            'disableCurrentPageButton' => true
                        ])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->
<?php
$msg = Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : '' ;
$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        var message = "{$msg}";
        if(message.length > 0){
            modal.msg(message);
        }
        
        //取消喜欢
        $('.cancel-btn').on('click', function(){
            var id = $(this).data('id')
                ,item = $(this).closest('div.item');
            
            modal.confirm("确定要取消该项吗？",{
                inPage:false
            },function(ele,obj){
                $.ajax({
                    url : "{$cancelUrl}",
                    data : {id:id},
                    type : 'post',
                    success : function(d){
                        console.log(d);
                        if(d.errno === 0){
                            item.remove();
                        }
                        modal.msg(d.message);
                    }
                });
                return true;
            });
            
        });
        
        
        
        
        
    });

    
JS;
$this->registerJs($jsStr);
?>




