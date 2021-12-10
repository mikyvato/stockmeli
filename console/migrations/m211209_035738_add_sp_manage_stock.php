<?php

use yii\db\Migration;

/**
 * Class m211209_035738_add_sp_manage_stock
 */
class m211209_035738_add_sp_manage_stock extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $db = Yii::$app->db;

        $db->createCommand('
        DROP FUNCTION IF EXISTS manage_stock;
        DROP PROCEDURE IF EXISTS update_stock;
        ')->execute();

        $db->createCommand('
        CREATE FUNCTION `manage_stock`(p_name varchar(120), p_ubication varchar(20), p_ubication_id integer, p_quantity integer, p_created_by integer, p_updated_by integer)
        RETURNS INTEGER
        BEGIN
            DECLARE v_result integer;
            IF EXISTS (SELECT 1 FROM stock WHERE name = p_name AND ubication = p_ubication AND ubication_id = p_ubication_id) THEN
                UPDATE stock SET quantity = quantity + p_quantity, updated_by = p_updated_by, updated_at = now() 
                WHERE name = p_name AND ubication = p_ubication AND ubication_id = p_ubication_id;
                SET v_result = 1;
            ELSE
                INSERT INTO stock 
                (name, ubication, ubication_id, quantity, created_by, updated_by, created_at, updated_at)
                VALUES
                (p_name, p_ubication, p_ubication_id, p_quantity, p_created_by, p_updated_by, now(), now());
                SET v_result = 2;
            END IF;

            RETURN v_result;
        END
        ')->execute();

        $db->createCommand('
        CREATE PROCEDURE `update_stock`(p_moveType integer, p_name varchar(120), p_ubication varchar(20), p_ubication_id integer, p_quantity integer, p_created_by integer, p_updated_by integer)
        BEGIN
            DECLARE v_result integer;
            DECLARE v_quantity integer;

            IF p_moveType = 1 THEN -- sumar elementos al stock
                SET v_result = manage_stock(p_name, p_ubication, p_ubication_id, p_quantity, p_created_by, p_updated_by); 
            ELSE -- restar elementos al Stock
                SET v_result = manage_stock(p_name, p_ubication, p_ubication_id, (p_quantity * -1), p_created_by, p_updated_by); 
                SELECT quantity FROM stock WHERE name = p_name AND ubication = p_ubication AND ubication_id = p_ubication_id INTO v_quantity;
                IF v_quantity = 0 THEN
                    DELETE FROM stock WHERE name = p_name AND ubication = p_ubication AND ubication_id = p_ubication_id;
                END IF;
            END IF;
        END
        ')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211209_035738_add_sp_manage_stock was reverted.\n";
        $db = Yii::$app->db;

        $db->createCommand('
        DROP FUNCTION IF EXISTS manage_stock;
        DROP PROCEDURE IF EXISTS update_stock;
        ')->execute();
    }
}
