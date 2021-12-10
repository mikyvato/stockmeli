<?php

/** User: Miguel Prieto */

namespace backend\controllers;

use Yii;
use backend\resource\User;
use backend\resource\LoginForm;

/**
 * Class User
 * 
 * @author Miguel Prieto <miguelprieto@outlook.com.ar>
 * @package backend\controllers
 */
class UserController extends ActiveController
{
    public $modelClass = User::class;

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return $model->getUser();
        }

        Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];
    }
}
