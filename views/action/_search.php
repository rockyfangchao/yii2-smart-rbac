<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model smart\rbac\models\ActionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="action-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'action_id') ?>

    <?= $form->field($model, 'action_title') ?>

    <?= $form->field($model, 'ctrl_title') ?>

    <?= $form->field($model, 'module_title') ?>

    <?= $form->field($model, 'route') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
