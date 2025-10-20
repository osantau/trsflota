<?php

use yii\db\Migration;

class m251017_181909_modify_documento_transport_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_vehicle_transport_order','{{%vehicle}}');       
        $this->dropIndex('idx_unique_documento_dateordered_partner_id','{{%transport_order}}');        
        // $this->dropIndex('idx_partner_id','{{%transport_order}}');
        $this->alterColumn('{{%transport_order}}','documentno','varchar(500)'); 
        $this->execute("
        ALTER DATABASE trsflota CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`cities` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`countries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`driver` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`invoice` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`location` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`migration` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`partner` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`payment` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`regions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`session` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`states` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`subregions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`transport_order` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`user` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `trsflota`.`vehicle` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        ALTER TABLE `transport_order` CHANGE `dateordered` `dateordered` DATE NULL DEFAULT NULL;
        update `trsflota`.`invoice` i set partner_id  = (select id from partner where trim(name)=trim(i.partener));
        update `trsflota`.`payment`  set nr_cmd_trs = replace(nr_cmd_trs,'.0','')  where nr_cmd_trs like '%.0%'; 
        update `trsflota`.`payment`  set nr_factura = replace(nr_factura,'.0','')  where nr_factura like '%.0%'; 
        ");      
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251017_181909_modify_documento_transport_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251017_181909_modify_documento_transport_order cannot be reverted.\n";

        return false;
    }
    */
}
