<?php

/** User: Miguel Prieto */

namespace backend\controllers;

use common\models\Ubication;
use common\models\search\UbicationSearch;

/**
 * Class ubication
 * 
 * @author Miguel Prieto <miguelprieto@outlook.com.ar>
 * @package backend\controllers
 */
class UbicationController extends ActiveController
{
    public $modelClass = Ubication::class;

    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        $verbs = parent::verbs();
        array_merge($verbs, ['report', ['GET']]);

        return $verbs;
    }
}
