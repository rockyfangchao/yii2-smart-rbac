<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model smart\rbac\models\Auth */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'auth_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_desc')->textarea(['rows' => 6]) ?>


    <div class="step-title m-b-10"><h3>Action信息</h3></div>


    <div >
        <?php if ($actions) foreach ($actions as $module_title => $ctrls): ?>
            <ul class="list-group list-unstyled">
                <li class="list-group-item">
                    <label>
                    <input type="checkbox" class="module_name_check" value="<?= $module_title ?>">
                    <?= $module_title ?>
                    </label>
                </li>
                <li class="list-group-item" style="padding-left:30px;">
                    <?php if ($ctrls) foreach ($ctrls as $ctrl_title => $acts): ?>
                        <dl class="dl-horizontal">
                            <dt style="text-align:left;border-right:1px dashed lightgray;width:10%;">
                                <label >
                                <input type="checkbox" class="module_name_check" value="<?= $ctrl_title ?>">
                                <?= $ctrl_title ?>
                                </label>
                            </dt>
                            <dd  style="margin-left:150px;">
                                <ul class=" list-inline">
                                    <?php if ($acts) foreach ($acts as $act): ?>
                                        <li >
                                            <label style="font-weight:normal;">
                                                <?= Html::input('checkbox', 'Auth[action_ids][]', $act['action_id'], ['checked' => in_array($act['action_id'], $model->action_id_array)]) ?>
                                                <?= $act['action_title'] ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>

                                </ul>
                            </dd>
                        </dl>
                    <?php endforeach; ?>

                </li>
            </ul>
            <br/>
        <?php endforeach; ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->beginBlock('suibian') ?>

$('.module_name_check').on('click', function () {
    if ($(this).is(':checked')) {
        $(this).parents().siblings('li,dt,dd').find('input[type=checkbox]').prop('checked', true);
    } else {
        $(this).parents().siblings('li,dt,dd').find('input[type=checkbox]').prop('checked', false);
    }
});

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['suibian']); ?>
