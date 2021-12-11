<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%device}}`.
 */
class m211210_173105_add_lastok_column_lastbad_column_to_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%device}}', 'lastok', $this->dateTime());
        $this->addColumn('{{%device}}', 'lastbad', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%device}}', 'lastok');
        $this->dropColumn('{{%device}}', 'lastbad');
    }
}
