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
            'password' => $this->string(),
            'status' => $this->smallInteger()->notNull(),
            'faculty_id' => $this->integer(),
            'office_id' => $this->integer(),
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
            'footer_information' => $this->string(200),
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
            'birthday' => $this->date()->notNull(),
            'tin_number' => $this->string(50)->notNull(),
            'nationality' => $this->string(150)->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        /* Education table */
        $this->createTable('{{%education}}', [
            'id' => $this->primaryKey(),
            'faculty_id' => $this->integer()->notNull(),
            'degree' => $this->string(100)->notNull(),
            'school' => $this->string(150)->notNull(),
            'date_graduate' => $this->string(20)->notNull(),
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
            'content' => $this->text()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        /* Notice table */
        $this->createTable('{{%notice}}', [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->integer()->notNull(),
            'faculty_id' => $this->integer()->notNull(),
            'template_id' => $this->integer()->notNull(),
            'semester' => $this->char(1)->notNull(),
            'academic_year' => $this->char(9)->notNull(),
            'date_course_start' => $this->date()->notNull(),
            'date_final_exam' => $this->date()->notNull(),
            'date_submission' => $this->date()->notNull(),
            'reference_number' => $this->string(7)->notNull(),
        ]);

        /* Storage table */
        $this->createTable('{{%storage}}', [
            'id' => $this->bigPrimaryKey(),
            'notice_id' => $this->bigInteger()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'location' => $this->string(500)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-user-faculty_id-faculty-id', '{{%user}}', 'faculty_id', '{{%faculty}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-user-office_id-office-id', '{{%user}}', 'office_id', '{{%office}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-auth-user_id-user-id', '{{%auth}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-faculty-designation_id-designation-id', '{{%faculty}}', 'designation_id', '{{%designation}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-education-faculty_id-faculty-id', '{{%education}}', 'faculty_id', '{{%faculty}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-program-office_id-office-id', '{{%program}}', 'office_id', '{{%office}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-course-program_id-program-id', '{{%course}}', 'program_id', '{{%program}}', 'id', '
            RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-facultycourse-faculty_id-faculty-id', '{{%facultycourse}}', 'faculty_id', '{{%faculty}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-facultycourse-course_id-course-id', '{{%facultycourse}}', 'course_id', '{{%course}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-template-user_id-user-id', '{{%template}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-notice-user_id-user-id', '{{%notice}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-notice-faculty_id-faculty-id', '{{%notice}}', 'faculty_id', '{{%faculty}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-notice-template_id-template-id', '{{%notice}}', 'template_id', '{{%template}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-storage-notice_id-notice-id', '{{%storage}}', 'notice_id', '{{%notice}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-storage-course_id-course-id', '{{%storage}}', 'course_id', '{{%course}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-user-faculty_id-faculty-id', '{{%user}}');
        $this->dropForeignKey('fk-auth-user_id-user-id', '{{%auth}}');
        $this->dropForeignKey('fk-user-office_id-office-id', '{{%user}}');
        $this->dropForeignKey('fk-faculty-designation_id-designation-id', '{{%faculty}}');
        $this->dropForeignKey('fk-program-office_id-office-id', '{{%program}}');
        $this->dropForeignKey('fk-course-program_id-program-id', '{{%course}}');
        $this->dropForeignKey('fk-facultycourse-faculty_id-faculty-id', '{{%facultycourse}}');
        $this->dropForeignKey('fk-facultycourse-course_id-course-id', '{{%facultycourse}}');
        $this->dropForeignKey('fk-template-user_id-user-id', '{{%template}}');
        $this->dropForeignKey('fk-notice-user_id-user-id', '{{%notice}}');
        $this->dropForeignKey('fk-notice-faculty_id-faculty-id', '{{%notice}}');
        $this->dropForeignKey('fk-notice-template_id-template-id', '{{%notice}}');
        $this->dropForeignKey('fk-storage-notice_id-notice-id', '{{%storage}}');
        $this->dropForeignKey('fk-storage-course_id-course-id', '{{%storage}}');
        $this->dropForeignKey('fk-education-faculty_id-faculty-id', '{{%education}}');

        $this->dropTable('{{%office}}');
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%designation}}');
        $this->dropTable('{{%faculty}}');
        $this->dropTable('{{%education}}');
        $this->dropTable('{{%program}}');
        $this->dropTable('{{%course}}');
        $this->dropTable('{{%facultycourse}}');
        $this->dropTable('{{%template}}');
        $this->dropTable('{{%notice}}');
        $this->dropTable('{{%storage}}');
    }
}
