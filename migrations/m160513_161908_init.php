<?php

use yii\db\Migration;

class m160513_161908_init extends Migration
{
    public function up()
    {
        /* User table */
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(32)->notNull(),
            'email' => $this->string(255)->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'office_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        /* Auth table */
        $this->createTable('{{%auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string(255)->notNull(),
            'source_id' => $this->string(255)->notNull(),
        ]);

        /* Office table */
        $this->createTable('{{%office}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(15)->notNull(),
            'name' => $this->string(200)->notNull(),
        ]);

        $this->addForeignKey('fk-user-office_id-office-id', '{{%user}}', 'office_id', '{{%office}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-auth-user_id-user-id', '{{%auth}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-auth-user_id-user-id', '{{%auth}}');
        $this->dropForeignKey('fk-user-office_id-office-id', '{{%user}}');

        $this->dropTable('{{%office}}');
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%user}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
