<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%devices}}`.
 */
class m210905_134157_create_devices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%devices}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'ip_address' => $this->string(15)->notNull(),
            'sshport' => $this->smallInteger(5)->notNull(),
            'username' => $this->string(100)->notNull(),
            'password' => $this->string(100)->notNull(),
        ]);

        $this->createIndex('idx_unique_ip_address_sshport',
                'devices',
                ['ip_address', 'sshport'],
                true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%devices}}');
    }
}
