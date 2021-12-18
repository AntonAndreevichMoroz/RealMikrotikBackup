<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%device}}`.
 */
class m211218_130927_change_password_column_add_sshuse_column_sshkey_column_to_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%device}}', 'password', $this->string(100));
        $this->addColumn('{{%device}}', 'sshuse', $this->boolean()->notnull());
        $this->addColumn('{{%device}}', 'sshkey', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%device}}', 'password', $this->string(100)->notNull());
        $this->dropColumn('{{%device}}', 'sshuse');
        $this->dropColumn('{{%device}}', 'sshkey');
    }
}
