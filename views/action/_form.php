<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model smart\rbac\models\Action */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="action-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'action_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ctrl_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'module_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
