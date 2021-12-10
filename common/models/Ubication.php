<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%ubication}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $short_form
 * @property int $ubication_type_id
 * @property int $ubication_id
 * @property int $row_max
 * @property int $row_min
 * @property int $hall_min
 * @property int $hall_max
 * @property int $status
 * @property string|null $description
 * @property string $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property Stock[] $stocks
 * @property Ubication $ubication
 * @property UbicationType $ubicationType
 * @property Ubication[] $ubications
 * @property User $updatedBy
 */
class Ubication extends MyActiveRecord
{
    const DEPOSITO = 2;
    const AREA = 3;
    const PASILLO = 4;
    const FILA = 5;

    const CAPACITY = 100;
    const DIVERSITY = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ubication}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ubication_type_id', 'ubication_id'], 'required'],
            [['ubication_type_id', 'ubication_id', 'row_max', 'row_min', 'hall_max', 'hall_min', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 120],
            [['short_form'], 'string', 'max' => 4],
            [['description'], 'string', 'max' => 250],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['ubication_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ubication::class, 'targetAttribute' => ['ubication_id' => 'id']],
            [['ubication_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UbicationType::class, 'targetAttribute' => ['ubication_type_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'short_form' => 'Short Form',
            'ubication_type_id' => 'Ubication Type',
            'ubication_id' => 'Ubication',
            'row_max' => 'Row Max',
            'row_min' => 'Row Min',
            'hall_min' => 'Hall Min',
            'hall_max' => 'Hall Max',
            'status' => 'Status',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Stocks]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\StockQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::class, ['ubication_id' => 'id']);
    }

    /**
     * Gets query for [[Ubication]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UbicationQuery
     */
    public function getUbication()
    {
        return $this->hasOne(Ubication::class, ['id' => 'ubication_id']);
    }

    /**
     * Gets query for [[UbicationType]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UbicationTypeQuery
     */
    public function getUbicationType()
    {
        return $this->hasOne(UbicationType::class, ['id' => 'ubication_type_id']);
    }

    /**
     * Gets query for [[Ubications]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UbicationQuery
     */
    public function getUbications()
    {
        return $this->hasMany(Ubication::class, ['ubication_id' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\UbicationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UbicationQuery(get_called_class());
    }

    public static function getElement($name, $ubicationType)
    {
        return self::find()
            ->andWhere('ubication_type_id = :ubication_type_id', [':ubication_type_id' => $ubicationType])
            ->andWhere('short_form = :short_form', [':short_form' => $name])->one();
    }
}
