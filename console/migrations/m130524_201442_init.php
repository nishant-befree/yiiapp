<?php

use yii\db\Migration;

class m130524_201442_init extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(200),
            'auth_key' => $this->string(255),
            'access_token_expired_at' => $this->integer()->null(),
            'password_hash' => $this->string(255),
            'password_reset_token' => $this->string(255),
            'email' => $this->string(255),
            'unconfirmed_email' => $this->string(255),
            'confirmed_at' => $this->integer()->null(),
            'registration_ip' => $this->string(20),
            'last_login_at' => $this->integer()->null(),
            'last_login_ip' => $this->string(20),
            'blocked_at' => $this->integer()->null(),
            'status' => $this->integer(2)->defaultValue(10),
            'role' => $this->integer(11)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // creates index for table
        $this->createIndex(
                'idx-user', 'user', ['username', 'auth_key', 'password_hash', 'status']
        );

        $this->batchInsert('user', ['id', 'username', 'auth_key', 'access_token_expired_at', 'password_hash', 'password_reset_token', 'email', 'unconfirmed_email', 'confirmed_at', 'registration_ip', 'last_login_at', 'last_login_ip', 'blocked_at', 'status', 'role', 'created_at', 'updated_at'], [
            [1, 'admin', 'dVN8fzR_KzJ_lBrymfXI6qyH2QzyXYUU', NULL, password_hash('admin', PASSWORD_BCRYPT, ['cost' => 13]), NULL, 'admin@demo.com', 'admin@demo.com', NULL, '127.0.0.1', NULL, '127.0.0.1', NULL, 10, 99, time(), time()],
            [2, 'staff', 'Xm-zZRREtAIKsFlINVRLSw3U7llbx_5a', NULL, password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 13]), NULL, 'staff@demo.com', 'staff@demo.com', NULL, '127.0.0.1', NULL, '127.0.0.1', NULL, 10, 50, time(), time()],
            [3, 'user', 'rNXSqIas_43RdpG0e5_7d1W06iK8pXJ8', NULL, password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 13]), NULL, 'user@demo.com', 'user@demo.com',NULL, '127.0.0.1',NULL, '127.0.0.1', NULL, 10, 10, time(), time()],
        ]);
    }
    //$2y$13$9Gouh1ZbewVEh4bQIGsifOs8/RWW/7RIs0CAGNd7tapXFm9.WxiXS
    //$2y$13$QfHWVo.VKmE7.BX4b2NJqeAWNoAtpL1kbwtK8mV/E5n.JPpf75X/2
    

    public function safeDown() {
        $this->dropIndex('idx-user', 'user');

        $this->dropTable('{{%user}}');
    }

}
