<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model smart\rbac\models\Auth */

$this->title = 'Update Auth: ' . $model->auth_id;
$this->params['breadcrumbs'][] = ['label' => 'Auths', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->auth_id, 'url' => ['view', 'id' => $model->auth_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'actions' => $actions

    ]) ?>

</div>
