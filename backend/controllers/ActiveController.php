<?php

namespace backend\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;

/**
 * Class ActiveController
 *
 * @author Miguel Prieto <miguelprieto@outlook.com.ar>
 * @package backend\controllers
 */
class ActiveController extends \yii\rest\ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];

        return $behaviors;
    }

    /**
     * @param string $action
     * @param Stock|Ubication|UbicationType|User $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete']) && $model->created_by !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("You do not have permission to change this record");
        }
    }
}
