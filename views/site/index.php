<?php
/* @var $this yii\web\View */
use yii\widgets\ListView;

$this->title = 'My Yii Application';

?>
<div class="site-index">
    <?= ListView::widget([
        'dataProvider' => $notificationsProvider,
        'itemView' => '_notification'
    ])?>
</div>
