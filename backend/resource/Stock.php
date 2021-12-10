<?php

namespace backend\resource;

use Yii;
use common\models\Ubication;
use yii\helpers\ArrayHelper;

/**
 * Stock model 
 */
class Stock extends \common\models\Stock
{
    private $_quantity;
    private $_diversity;

    /**
     * Set Total quantity 
     * @param double $value
     */
    public function setTotalQuantity($value)
    {
        $this->_quantity = empty($value) ? 0 : $value['quantity'];
    }

    /**
     * Get total quantity
     */
    public function getTotalQuantity()
    {
        if ($this->_quantity === null) $this->setTotalQuantity($this->sumArticulos);

        return $this->_quantity;
    }

    /**
     * Set diversity of article 
     * @param double $value
     */
    public function setTotalDiversity($value)
    {
        $this->_diversity = empty($value) ? 0 : $value['diversity'];
    }

    /**
     * Get diversity
     */
    public function getTotalDiversity()
    {
        if ($this->_diversity === null) $this->setTotalDiversity($this->countArticulos);

        return $this->_diversity;
    }

    /**
     * @return \app\models\User|UserResource|bool|null
     */
    public function validateUbication($type, $quantity = 0)
    {
        if ($type === Stock::TYPE_CREATE)
            $futureQuantity = $this->totalQuantity + $this->quantity;
        else
            $futureQuantity = $this->totalQuantity - $quantity;

        if (intval($futureQuantity) > Ubication::CAPACITY)  /// -- Chequeo de Capacidad (No debe superar los 100)
            return ['error' => 'Supera la capacidad del Stock'];
        if (intval($futureQuantity) < 0) {                   /// -- Chequeo que el stock no quede negativo
            return ['error' => 'No posee productos suficientes'];
        }

        $diversity = $this->totalDiversity;
        if (intval($diversity) === Ubication::DIVERSITY) { /// -- Chqueo Clases de productos (No debe superar los 3)
            $listaDiversidad = Stock::getArrayDiversity($this->ubication);
            if (!in_array($this->name, $listaDiversidad))
                return ["error", "Ya existen 3 clases diferentes de productos almacenados"]; // -- Si no existe ejecuto una exeption
        }

        if ($this->ubication !== null) {
            list($area, $hall, $row, $cara) = explode("-", $this->ubication);

            // -- Valor Valido para Cara
            if (!array_key_exists($cara, $this->side))
                return ['error' => 'ERROR al indicar la cara en la estanteria'];

            // -- Valor Valido para hall
            $ubication = Ubication::getElement($area, Ubication::AREA);
            if ($ubication->hall_min > $hall  || $ubication->hall_max < $hall)
                return ['error' => 'ERROR al indicar el hall'];

            // -- Valor Valido para row
            if ($ubication->row_min > $row || $ubication->row_max < $row)
                return ['error' => 'ERROR al indicar row'];

            // -- Valor Valido para area            
            if ($ubication->ubication_id !== $this->ubication_id)
                return ['error' => 'ERROR al indicar area'];

            return true;
        }
    }

    /**
     * Suma Cantidad de Articulos usando Ubicacion
     */
    public function getSumArticulos()
    {
        return $this->find()
            ->select(['quantity' => 'sum(quantity)'])
            ->andWhere('ubication = :ubication', [':ubication' => $this->ubication])
            ->groupBy('ubication')
            ->asArray(true);
    }

    /**
     * Cuanta la diversidad de Articulos usando Ubicacion
     */
    public function getCountArticulos()
    {
        return $this->find()
            ->select(['diversity' => 'count(name)'])
            ->andWhere('ubication = :ubication', [':ubication' => $this->ubication])
            ->groupBy('ubication')
            ->asArray(true);
    }

    /**
     * @inheritdoc
     * Retorna un array clave valor (id => nombre)
     * @param string indicando el criterio de busqueda
     * @return Array.
     */
    public static function getArrayDiversity($ubication)
    {
        return ArrayHelper::map(self::find()
            ->andWhere('ubication = :ubication', [':ubication' => $ubication])
            ->orderBy('name')
            ->asArray()
            ->all(), 'id', 'name');
    }
}
