<?php

use yii\db\Migration;

class m251022_162203_add_field_regno_tranasport_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transport_order}}','regno','varchar(100)'); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251022_162203_add_field_regno_tranasport_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251022_162203_add_field_regno_tranasport_order cannot be reverted.\n";

        return false;
    }
    */
}
