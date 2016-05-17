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

        /* Designation table */
        $this->createTable('{{%designation}}', [
            'id' => $this->primaryKey(),
            'abbreviation' => $this->string(50)->notNull(),
            'title' => $this->string(100)->notNull(),
        ]);

        /* Faculty table */
        $this->createTable('{{%faculty}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(50)->notNull(),
            'last_name' => $this->string(50)->notNull(),
            'middle_name' => $this->string(50)->notNull(),
            'designation_id' => $this->integer()->notNull(),
            'email' => $this->string(150)->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        /* Program table */
        $this->createTable('{{%program}}', [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer()->notNull(),
            'code' => $this->string(20)->notNull(),
            'name' => $this->string(150)->notNull(),
        ]);

        /* Course table */
        $this->createTable('{{%course}}', [
            'id' => $this->primaryKey(),
            'program_id' => $this->integer()->notNull(),
            'code' => $this->string(20)->notNull(),
            'title' => $this->string(150)->notNull(),
        ]);

        /* Facultycourse table */
        $this->createTable('{{%facultycourse}}', [
            'faculty_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
        ]);

        /* Template table */
        $this->createTable('{{%template}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(50)->notNull(),
            'content' => $this->text(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-user-office_id-office-id', '{{%user}}', 'office_id', '{{%office}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-auth-user_id-user-id', '{{%auth}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-faculty-designation_id-designation-id', '{{%faculty}}', 'designation_id', '{{%designation}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-program-office_id-office-id', '{{%program}}', 'office_id', '{{%office}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-course-program_id-program-id', '{{%course}}', 'program_id', '{{%program}}', 'id', '
            RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-facultycourse-faculty_id-faculty-id', '{{%facultycourse}}', 'faculty_id', '{{%faculty}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-facultycourse-course_id-course-id', '{{%facultycourse}}', 'course_id', '{{%course}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-template-user_id-user-id', '{{%template}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-auth-user_id-user-id', '{{%auth}}');
        $this->dropForeignKey('fk-user-office_id-office-id', '{{%user}}');
        $this->dropForeignKey('fk-faculty-designation_id-designation-id', '{{%faculty}}');
        $this->dropForeignKey('fk-program-office_id-office-id', '{{%program}}');
        $this->dropForeignKey('fk-course-program_id-program-id', '{{%course}}');
        $this->dropForeignKey('fk-facultycourse-faculty_id-faculty-id', '{{%facultycourse}}');
        $this->dropForeignKey('fk-facultycourse-course_id-course-id', '{{%facultycourse}}');
        $this->dropForeignKey('fk-template-user_id-user-id', '{{%template}}');

        $this->dropTable('{{%office}}');
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%designation}}');
        $this->dropTable('{{%faculty}}');
        $this->dropTable('{{%program}}');
        $this->dropTable('{{%course}}');
        $this->dropTable('{{%facultycourse}}');
        $this->dropTable('{{%template}}');
    }
}
