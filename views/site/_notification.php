<?php
/* 
 * @author Ivan Nikiforov
 * Apr 19, 2016
 */

/* @var $model \app\models\NotificationBrowser */

use yii\helpers\Html;

$date = Yii::$app->formatter->asDatetime($model->created_at, 'd MMMM yyyy H:mm');

?>
<div class="alert <?=$model->isNew() ? 'alert-warning' : 'alert-info'?>" role="alert">
    <?php if($model->isNew()) { echo Html::button('Прочитано', ['class' => 'btn btn-default pull-right mark-as-read', 'data-id' => $model->id]);}?>
    <h4><?=$date.' - '.Html::encode($model->subject)?></h4> <?=Html::encode($model->text)?>
</div>