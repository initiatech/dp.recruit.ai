<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'signed' => false,
        ]);

        $table->addColumn('org_id', 'integer', ['null' => true, 'signed' => false, 'comment' => 'Reserved for multi-tenant support'])
              ->addColumn('full_name', 'string', ['limit' => 150, 'null' => false])
              ->addColumn('email', 'string', ['limit' => 190, 'null' => false])
              ->addColumn('role', 'enum', ['values' => ['admin', 'recruiter', 'viewer'], 'default' => 'recruiter', 'null' => false])
              ->addColumn('status', 'enum', ['values' => ['active', 'disabled'], 'default' => 'active', 'null' => false])
              ->addColumn('password_hash', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addIndex(['email'], ['unique' => true, 'name' => 'idx_users_email'])
              ->create();
    }
}
