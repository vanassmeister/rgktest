<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\FormHelper;
use app\models\Notification;

/* @var $this yii\web\View */
/* @var $model app\models\Notification */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('js/notification.js', ['depends' => [
        'app\assets\AppAsset',
]]);

?>

<div class="notification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'event')->dropDownList($model->getEventList()) ?>

    <?= $form->field($model, 'sender_id')->dropDownList(FormHelper::getUserList()) ?>

    <?= $form->field($model, 'recipient_id')->dropDownList(['' => 'All users'] + FormHelper::getUserList()) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>
    <div id="notification-placeholders"></div>
    
    <?= $form->field($model, 'notificationTypeIds')->listBox(Notification::getTypeList(), ['multiple' => 'true'])?> 
    
    <?= $form->errorSummary($model); ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
