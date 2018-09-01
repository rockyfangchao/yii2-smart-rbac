<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model smart\rbac\models\AuthSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'auth_id') ?>

    <?= $form->field($model, 'auth_name') ?>

    <?= $form->field($model, 'auth_desc') ?>

    <?= $form->field($model, 'action_ids') ?>

    <?= $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
