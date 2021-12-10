<?php

/** User: Miguel Prieto */

namespace backend\controllers;

use Yii;
use backend\resource\Stock;
use common\models\Ubication;
use common\models\search\StockSearch;
use yii\web\NotFoundHttpException;
use  linslin\yii2\curl\Curl;

/**
 * Class Stock
 * 
 * @author Miguel Prieto <miguelprieto@outlook.com.ar>
 * @package backend\controllers
 */
class StockController extends ActiveController
{
    public $modelClass = Stock::class;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);

        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        $verbs = parent::verbs();
        array_merge($verbs, ['remove', ['POST']]);
        array_merge($verbs, ['report', ['GET']]);
        array_merge($verbs, ['search', ['GET']]);

        return $verbs;
    }

    /**
     * Creates or Update Stock model.
     * If creation is successful, you will see teh success message.
     * @return array message
     */
    public function actionCreate()
    {
        $model = new Stock();

        if ($model->load(Yii::$app->request->post(), ''))
            if (!$this->checkLogisticType($model->name))
                return ["error", "El producto no esta en nuestros depositos"];
            else {
                $validationUbication = $model->validateUbication(Stock::TYPE_CREATE);
                if ($validationUbication === true && $model->validate()) {
                    return $this->executeSP($model, Stock::TYPE_CREATE);
                } else
                if ($model->getErrors())
                    return $model->getErrors();
                else
                    return $validationUbication;
            }
        else {
            return ["error", "No se pudo tomar el POST"];
        }
    }

    /**
     * Remove units from Stock model.
     * If remove is successful, you will see the success message.
     * @return array message
     */
    public function actionRemove()
    {
        $model = new Stock();

        if (Yii::$app->request->post()) {
            $request = Yii::$app->request;
            $name = $request->post('name');
            $ubicationModel = Ubication::getElement($request->post('deposito'), Ubication::DEPOSITO);
            $ubication = $request->post('ubication');
            $quantity = $request->post('quantity');
            $model = $this->findModel($name, $ubicationModel->id, $ubication);
            $validationUbication = $model->validateUbication(Stock::TYPE_REMOVE, $quantity);
            if ($validationUbication === true) {
                $model->quantity = $quantity;
                return $this->executeSP($model, Stock::TYPE_REMOVE);
            } else
                return $validationUbication;
        } else {
            return ["error", "No se pudo tomar la accion"];
        }
    }

    /**
     * This Method just execute the Store Procedure in the databae.
     * If it is successful, return a success message.
     * @param objet $model
     * @param integer $type indicate if you will add or remove elements
     * @return array 
     */
    public function executeSP($model, $type)
    {
        $db = Yii::$app->db;
        $DB_transaction = $db->beginTransaction();

        $command = $db->createCommand('call update_stock(:moveType, :name, :ubication, :ubication_id, :quantity, :created_by, :updated_by)');
        $command = $command->bindValue(':moveType', $type);
        $command = $command->bindValue(':name', $model->name);
        $command = $command->bindValue(':ubication', $model->ubication);
        $command = $command->bindValue(':ubication_id', $model->ubication_id);
        $command = $command->bindValue(':quantity', $model->quantity);
        $command = $command->bindValue(':created_by', Yii::$app->user->id);
        $command = $command->bindValue(':updated_by', Yii::$app->user->id);
        try {
            $command->execute();
            $DB_transaction->commit();
            return ['success', ' Transaccion finalizada con Exito!.'];
        } catch (\Exception $e) {
            $DB_transaction->rollBack();
            return ['error', ' ERROR al cerrar PTO 1' . $e];
        }
    }

    /**
     * Finds the Stock model based on $name, $ubication_id and $ubication.
     * If the model is not found, a 404 HTTP exception will be thrown.   
     * @param string $name
     * @param integer $ubication_id
     * @param string $ubication
     * @return Stock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($name, $ubication_id, $ubication)
    {
        $model = Stock::find()
            ->andWhere('name = :name', [':name' => $name])
            ->andWhere('ubication_id = :ubication_id', [':ubication_id' => $ubication_id])
            ->andWhere('ubication = :ubication', [':ubication' => $ubication])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('El Articulo requerido no existe.');
        }
    }

    public function actionReport()
    {
        $searchModel = new StockSearch();
        $deposit = \Yii::$app->request->get('deposit');
        $ubication = \Yii::$app->request->get('ubication');

        $searchModel->deposit = $deposit;
        $searchModel->ubication = $ubication;

        $dataProvider = $searchModel->searchDeposit();

        return $dataProvider->getModels();
    }

    public function actionSearch()
    {
        $searchModel = new StockSearch();
        $deposit = \Yii::$app->request->get('deposit');
        $product = \Yii::$app->request->get('product');

        $searchModel->deposit = $deposit;
        $searchModel->name = $product;

        $dataProvider = $searchModel->searchProduct();

        return $dataProvider->getModels();
    }

    public function checkLogisticType($name)
    {
        $curl = new Curl();
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response = $curl->get('https://api.mercadolibre.com/items/' . $name, true);

        $value = json_decode($response);
        if ($value->shipping->logistic_type === 'fulfillment')
            return true;
        else
            return false;
    }
}
