<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%stock}}".
 *
 * @property int $id
 * @property string $name
 * @property string $ubication
 * @property int $ubication_id
 * @property int $quantity
 * @property string $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property Ubication $ubication0
 * @property User $updatedBy
 */
class Stock extends MyActiveRecord
{
    const TYPE_CREATE = 1;
    const TYPE_REMOVE = 2;

    public static $side = ['IZ' => 'Izquierda', 'DR' => 'Derecha'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%stock}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ubication', 'ubication_id'], 'required'],
            [['ubication_id', 'quantity', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 120],
            [['ubication'], 'string', 'max' => 20],
            ['ubication', 'match', 'pattern' => '/^([A-Z]){2}-\d{2}-\d{2}-([A-Z]){2}/'],
            [['ubication'], 'validateUbication'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['ubication_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ubication::class, 'targetAttribute' => ['ubication_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * Validates the ubication.
     * This method serves as the inline validation for ubication.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'ubication' => 'Ubication',
            'ubication_id' => 'Ubication ID',
            'quantity' => 'Quantity',
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
     * Gets query for [[Ubication0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UbicationQuery
     */
    public function getUbication0()
    {
        return $this->hasOne(Ubication::class, ['id' => 'ubication_id']);
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
     * @return \common\models\query\StockQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\StockQuery(get_called_class());
    }

    /**
     * @inheritdoc
     * Retorna un array clave valor (id => nombre)
     * @param integer indica clave del array
     * @return @Array.
     */
    public static function getSide($key = null)
    {
        if ($key !== null)
            return self::$side[$key];
        return self::$side;
    }
}
