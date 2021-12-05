<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%devices}}`.
 */
class m211201_204832_add_active_column_laststatus_column_to_devices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%devices}}', 'active', $this->boolean()->notNull());
        $this->addColumn('{{%devices}}', 'laststatus', $this->string(3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%devices}}', 'active');
        $this->dropColumn('{{%devices}}', 'laststatus');
    }
}
