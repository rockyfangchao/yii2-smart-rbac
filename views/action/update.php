<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model smart\rbac\models\Action */

$this->title = 'Update Action: ' . $model->action_id;
$this->params['breadcrumbs'][] = ['label' => 'Actions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->action_id, 'url' => ['view', 'id' => $model->action_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="action-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
