<?php

namespace backend\resource;

/**
 * Class User
 *
 * @author Miguel Prieto <miguelprieto@outlook.com.ar>
 * @package backend\resource
 */
class User extends \common\models\User
{
    public function fields()
    {
        return ['username', 'email', 'access_token'];
    }
}
