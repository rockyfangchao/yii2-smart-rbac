<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model smart\rbac\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role_desc')->textarea(['maxlength' => true, 'rows' => 6]) ?>

    <?= $form->field($model, 'auth_ids')->checkboxList(\smart\rbac\models\Auth::getAuthNameArray(),['value'=>explode(',',$model->auth_ids)])?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
