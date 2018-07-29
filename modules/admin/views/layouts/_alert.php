<?php
$session = \Yii::$app->session;
if($session->hasFlash('success')){
    $type = 'green';
    $message = $session->getFlash('success');
}else{
    $type = 'red';
    $message = $session->getFlash('error');
}
?>
<div class="ui <?= $type?> message"><i class="close icon"></i><?= $message?></div>