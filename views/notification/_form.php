<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Article;

/* @var $this yii\web\View */
/* @var $model app\models\Notification */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="notification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'event')->dropDownList($model->getEventList()) ?>

    <?= $form->field($model, 'sender_id')->dropDownList($model->getUserList()) ?>
    <div class="checkbox">
        <label>
            <input type="checkbox"> All senders
        </label>
    </div>  

    <?= $form->field($model, 'recipient_id')->dropDownList($model->getUserList()) ?>
    <div class="checkbox">
        <label>
            <input type="checkbox"> All recipients
        </label>
    </div>  

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'notificationTypes')->listBox($model->getTypeList(), ['multiple' => 'true'])?>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
