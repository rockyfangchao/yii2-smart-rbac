<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel smart\rbac\models\ActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--        --><? /*= Html::a('Create Action', ['create'], ['class' => 'btn btn-success']) */ ?>
        <?= Html::a('Refresh Action', ['refresh'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\Column',
                'header' => 'ID',
                'content' => function ($model) {
                    return $model->action_id;
                }
            ],
            //'action_id',
            'action_title',
            'ctrl_title',
            'module_title',
            'route',
            //'create_at',
            [
                'label' => '是否是菜单',
                'value' => function ($model) {
                    if ($model->menu) {
                        return '是';
                    }
                }
            ],
            'update_at:datetime',
            [
                'label' => '',
                'value' => function ($model) {
                    if (!$model->menu) {
                        return Html::a('设置为菜单', ['menu/create', 'action_id' => $model->action_id], ['class' => 'btn-link']);

                    }
                },
                'format' => 'raw'
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
