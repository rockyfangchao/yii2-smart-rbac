<?php

namespace smart\rbac\controllers;

use yii\web\Controller;

/**
 * Default controller for the `Module` module
 */
class BaseController extends Controller
{

    public function init(){
        parent::init();

        $this->request = \Yii::$app->getRequest();

    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
