<?php

namespace common\models\search;

use yii\data\ActiveDataProvider;
use \common\models\Ubication;

/**
 * UbicationSearch represents the model behind the search form about `app\models\Ubication`. 
 */
class UbicationSearch extends Ubication
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchDeposit()
    {
        $query = Ubication::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //$this->load($params);
        /*
        // grid filtering conditions
        $query->andFilterWhere([
            'unidad_medida_id' => $this->unidad_medida_id,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);*/

        return $dataProvider;
    }
}
