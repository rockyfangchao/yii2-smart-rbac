<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel smart\rbac\models\AuthSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auths';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Auth', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\Column',
                'header' => 'ID',
                'content' => function ($model) {
                    return $model->auth_id;
                }
            ],
            //'auth_id',
            'auth_name',
            'auth_desc',
            //'action_ids:ntext',
            'create_at:datetime',
            'update_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
