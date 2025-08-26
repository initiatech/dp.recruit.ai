<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCoreTables extends AbstractMigration
{
    public function change(): void
    {
        // candidates table
        $candidates = $this->table('candidates', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $candidates->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('full_name', 'string', ['limit' => 150])
            ->addColumn('email', 'string', ['limit' => 190, 'null' => true])
            ->addColumn('phone', 'string', ['limit' => 40, 'null' => true])
            ->addColumn('cv_text', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM, 'null' => true])
            ->addColumn('source', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['email'])
            ->addIndex(['phone'])
            ->create();

        // jobs table
        $jobs = $this->table('jobs', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $jobs->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('title', 'string', ['limit' => 150])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('required_fields_json', 'json')
            ->addColumn('must_have', 'json', ['null' => true])
            ->addColumn('nice_to_have', 'json', ['null' => true])
            ->addColumn('block_rules', 'json', ['null' => true])
            ->addColumn('heat_threshold', 'integer', ['default' => 70])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['title'])
            ->create();

        // users table
        $users = $this->table('users', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $users->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('org_id', 'integer', ['null' => true, 'signed' => false, 'comment' => 'Reserved for multi-tenant support'])
            ->addColumn('full_name', 'string', ['limit' => 150])
            ->addColumn('email', 'string', ['limit' => 190, 'unique' => true])
            ->addColumn('role', 'enum', ['values' => ['admin', 'recruiter', 'viewer'], 'default' => 'recruiter'])
            ->addColumn('status', 'enum', ['values' => ['active', 'disabled'], 'default' => 'active'])
            ->addColumn('password_hash', 'string', ['limit' => 255])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();

        // labels table
        $labels = $this->table('labels', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $labels->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('org_id', 'integer', ['null' => true, 'signed' => false, 'comment' => 'Reserved for multi-tenant support'])
            ->addColumn('name', 'string', ['limit' => 60])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['name', 'org_id'], ['unique' => true])
            ->create();
    }
}
