<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use app\assets\AdminAsset;
use app\assets\RequireJsAsset;

RequireJsAsset::register($this);
AdminAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<?php

//提示信息
if(
    Yii::$app->session->hasFlash('success') ||
    Yii::$app->session->hasFlash('error')
){
    echo $this->render('_alert');
}

//内容
echo $content;
?>

<?php $this->endBody() ?>
<script>
    //关闭提示信息
    $('.message .close').on('click', function() {
        $(this).closest('.message').transition('fade');
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
