<?php

use common\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%ubication_type}}`.
 */
class m211206_202115_create_ubication_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ubication_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(120)->notNull(),
            'description' => $this->string(150),
            'status' => $this->integer()->defaultValue(1)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-ubication_type-created_by}}',
            '{{%ubication_type}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-ubication_type-created_by}}',
            '{{%ubication_type}}',
            'created_by',
            '{{%user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-ubication_type-updated_by}}',
            '{{%ubication_type}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-ubication_type-updated_by}}',
            '{{%ubication_type}}',
            'updated_by',
            '{{%user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $user = new User();
        $user->id = 1;
        $user->username = 'UserTest';
        $user->email = 'email@email';
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('12345678');
        $user->generateAccessToken();
        $user->generateEmailVerificationToken();
        $user->generateAuthKey();
        if ($user->save()) {
            echo "Usuario Generado con exito";
        } else {
            echo "Error al generar el Usuario";
            var_dump($user->errors);
        }

        $this->insert('{{%ubication_type}}', ['id' => 1, 'name' => 'NotDefined', 'description' => 'Internal uses only', 'status' => 0, 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d'), 'created_by' => 1, 'updated_by' => 1]);
        $this->insert('{{%ubication_type}}', ['id' => 2, 'name' => 'Deposito', 'description' => 'Deposito por pais', 'status' => 10, 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d'), 'created_by' => 1, 'updated_by' => 1]);
        $this->insert('{{%ubication_type}}', ['id' => 3, 'name' => 'Area', 'description' => 'Area o Sector', 'status' => 10, 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d'), 'created_by' => 1, 'updated_by' => 1]);
        $this->insert('{{%ubication_type}}', ['id' => 4, 'name' => 'Pasillo', 'description' => 'Pasillo del Sector', 'status' => 10, 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d'), 'created_by' => 1, 'updated_by' => 1]);
        $this->insert('{{%ubication_type}}', ['id' => 5, 'name' => 'Fila', 'description' => 'Fila del Sector', 'status' => 10, 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d'), 'created_by' => 1, 'updated_by' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops default record
        $this->delete('{{%ubication_type}}', ['id' => [1, 2, 3, 4]]);
        $this->delete('{{%user}}', ['id' => 1]);

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-ubication_type-created_by}}',
            '{{%ubication_type}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-ubication_type-created_by}}',
            '{{%ubication_type}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-ubication_type-updated_by}}',
            '{{%ubication_type}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-ubication_type-updated_by}}',
            '{{%ubication_type}}'
        );

        $this->dropTable('{{%ubication_type}}');
    }
}
