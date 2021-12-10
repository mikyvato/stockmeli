<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%stock}}`.
 */
class m211206_204716_create_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stock}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(120)->notNull(),
            'ubication' => $this->string(20)->notNull(),
            'ubication_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `ubication_id`
        $this->createIndex(
            '{{%idx-stock-ubication_id}}',
            '{{%stock}}',
            'ubication_id'
        );

        // add foreign key for table `{{%ubication}}`
        $this->addForeignKey(
            '{{%fk-stock-ubication_id}}',
            '{{%stock}}',
            'ubication_id',
            '{{%ubication}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-stock-created_by}}',
            '{{%stock}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-stock-created_by}}',
            '{{%stock}}',
            'created_by',
            '{{%user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-stock-updated_by}}',
            '{{%stock}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-stock-updated_by}}',
            '{{%stock}}',
            'updated_by',
            '{{%user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-stock-ubication_id}}',
            '{{%stock}}'
        );

        // drops index for column `ubication_id`
        $this->dropIndex(
            '{{%idx-stock-ubication_id}}',
            '{{%stock}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-stock-created_by}}',
            '{{%stock}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-stock-created_by}}',
            '{{%stock}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-stock-updated_by}}',
            '{{%stock}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-stock-updated_by}}',
            '{{%stock}}'
        );

        $this->dropTable('{{%stock}}');
    }
}
