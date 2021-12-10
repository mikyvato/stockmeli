<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\UbicationType]].
 *
 * @see \common\models\UbicationType
 */
class UbicationTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\UbicationType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\UbicationType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
