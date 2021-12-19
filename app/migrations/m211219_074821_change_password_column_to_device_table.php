<?php

use yii\db\Migration;

/**
 * Class m211219_074821_change_password_column_to_device_table
 */
class m211219_074821_change_password_column_to_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%device}}', 'password', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%device}}', 'password', $this->string(100));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211219_074821_change_password_column_to_device_table cannot be reverted.\n";

        return false;
    }
    */
}
