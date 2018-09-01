<?php

namespace smart\rbac\controllers;

use yii\web\Controller;

/**
 * Default controller for the `Module` module
 */
class DefaultController extends Controller
{
    protected $request;

    public function init(){
        parent::init();
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
