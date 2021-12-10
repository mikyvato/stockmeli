<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ubication}}`.
 */
class m211206_202316_create_ubication_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ubication}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(120)->notNull(),
            'short_form' => $this->string(4),
            'ubication_type_id' => $this->integer()->notNull(),
            'ubication_id' => $this->integer()->notNull(),
            'row_max' => $this->integer()->notNull()->defaultValue(0),
            'row_min' => $this->integer()->notNull()->defaultValue(0),
            'hall_min' => $this->integer()->notNull()->defaultValue(0),
            'hall_max' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->defaultValue(0)->notNull(),
            'description' => $this->string(250),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `ubication_type_id`
        $this->createIndex(
            '{{%idx-ubication-ubication_type_id}}',
            '{{%ubication}}',
            'ubication_type_id'
        );

        // add foreign key for table `{{%ubication_type}}`
        $this->addForeignKey(
            '{{%fk-ubication-ubication_type_id}}',
            '{{%ubication}}',
            'ubication_type_id',
            '{{%ubication_type}}',
            'id',
            'CASCADE'
        );

        // creates index for column `ubication_id`
        $this->createIndex(
            '{{%idx-ubication-ubication_id}}',
            '{{%ubication}}',
            'ubication_id'
        );

        // add foreign key for table `{{%ubication}}`
        $this->addForeignKey(
            '{{%fk-ubication-ubication_id}}',
            '{{%ubication}}',
            'ubication_id',
            '{{%ubication}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-ubication-created_by}}',
            '{{%ubication}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-ubication-created_by}}',
            '{{%ubication}}',
            'created_by',
            '{{%user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-ubication-updated_by}}',
            '{{%ubication}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-ubication-updated_by}}',
            '{{%ubication}}',
            'updated_by',
            '{{%user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        // INSERT default VALUE
        $this->insert('{{%ubication}}', [
            'id' => 1,
            'name' => 'NotDefined',
            'short_form' => 'NN',
            'ubication_type_id' => 1,
            'ubication_id' => 1,
            'row_max' => 0,
            'row_min' => 0,
            'hall_min' => 0,
            'hall_max' => 0,
            'description' => 'Internal use only',
            'status' => 0,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops default record
        $this->delete('{{%ubication}}', ['id' => 1]);

        $this->dropForeignKey(
            '{{%fk-ubication-ubication_type_id}}',
            '{{%ubication}}'
        );

        // drops index for column `ubication_type_id`
        $this->dropIndex(
            '{{%idx-ubication-ubication_type_id}}',
            '{{%ubication}}'
        );

        $this->dropForeignKey(
            '{{%fk-ubication-ubication_id}}',
            '{{%ubication}}'
        );

        // drops index for column `ubication_id`
        $this->dropIndex(
            '{{%idx-ubication-ubication_id}}',
            '{{%ubication}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-ubication-created_by}}',
            '{{%ubication}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-ubication-created_by}}',
            '{{%ubication}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-ubication-updated_by}}',
            '{{%ubication}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-ubication-updated_by}}',
            '{{%ubication}}'
        );

        $this->dropTable('{{%ubication}}');
    }
}
