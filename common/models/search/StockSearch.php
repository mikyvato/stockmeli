<?php

namespace common\models\search;

use yii\data\ActiveDataProvider;
use \common\models\Stock;

/**
 * StockSearch represents the model behind the search form about `app\models\Stock`. 
 */
class StockSearch extends Stock
{
    public $deposit;

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchDeposit()
    {
        $query = Stock::find();
        $query->select(['stock.name as name', 'stock.quantity as quantity', 'stock.ubication as ubication']);
        $query->leftJoin('ubication', 'ubication.id = stock.ubication_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere(['like', 'stock.ubication', $this->ubication]);
        $query->andFilterWhere(['like', 'ubication.short_form', $this->deposit]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchProduct()
    {
        $query = Stock::find();
        $query->select(['stock.name as name', 'stock.quantity as quantity', 'stock.ubication as ubication']);
        $query->leftJoin('ubication', 'ubication.id = stock.ubication_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere(['like', 'stock.name', $this->name]);
        $query->andFilterWhere(['like', 'ubication.short_form', $this->deposit]);

        return $dataProvider;
    }
}
