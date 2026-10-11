<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class PrepareTsa2 extends Migration
{
    public function up()
    {
        // Existing TSA1 tables and records are preserved. Empty installs also work.
        if (! $this->db->tableExists('tasks')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'auto_increment' => true],
                'title' => ['type' => 'VARCHAR', 'constraint' => 150],
                'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
                'task_date' => ['type' => 'DATE'],
                'created_at' => ['type' => 'DATETIME'],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('tasks');
        }
        if (! $this->db->tableExists('users')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'auto_increment' => true],
                'username' => ['type' => 'VARCHAR', 'constraint' => 50],
                'full_name' => ['type' => 'VARCHAR', 'constraint' => 100],
                'email' => ['type' => 'VARCHAR', 'constraint' => 100],
                'created_at' => ['type' => 'DATETIME'],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('username');
            $this->forge->createTable('users');
        }
        if (! $this->db->fieldExists('password', 'users')) {
            $this->forge->addColumn('users', [
                'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
            ]);
        }
        if (! $this->db->fieldExists('is_archived', 'tasks')) {
            $this->forge->addColumn('tasks', [
                'is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            ]);
        }
    }
    public function down()
    {
        // A rollback could erase existing TSA1 records or archived-state information.
        throw new \RuntimeException('This additive upgrade is forward-only. Restore your database backup to revert it.');
    }
}
