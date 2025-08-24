<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCoreTables extends AbstractMigration
{
    public function change(): void
    {
        // candidates table
        $this->table('candidates', ['id' => true, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci', 'signed' => false])
            ->addColumn('full_name', 'string', ['limit' => 150])
            ->addColumn('email', 'string', ['limit' => 190, 'null' => true])
            ->addColumn('phone', 'string', ['limit' => 40, 'null' => true])
            ->addColumn('cv_text', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM, 'null' => true])
            ->addColumn('source', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['email'])
            ->addIndex(['phone'])
            ->create();

        // jobs table
        $this->table('jobs', ['id' => true, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci', 'signed' => false])
            ->addColumn('title', 'string', ['limit' => 150])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('required_fields_json', 'json')
            ->addColumn('must_have', 'json', ['null' => true])
            ->addColumn('nice_to_have', 'json', ['null' => true])
            ->addColumn('block_rules', 'json', ['null' => true])
            ->addColumn('heat_threshold', 'integer', ['default' => 70])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['title'])
            ->create();

        // users table
        $this->table('users', ['id' => true, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci', 'signed' => false])
            ->addColumn('org_id', 'integer', ['null' => true, 'signed' => false, 'comment' => 'Reserved for multi-tenant support'])
            ->addColumn('full_name', 'string', ['limit' => 150])
            ->addColumn('email', 'string', ['limit' => 190, 'unique' => true])
            ->addColumn('role', 'enum', ['values' => ['admin', 'recruiter', 'viewer'], 'default' => 'recruiter'])
            ->addColumn('status', 'enum', ['values' => ['active', 'disabled'], 'default' => 'active'])
            ->addColumn('password_hash', 'string', ['limit' => 255])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();

        // labels table
        $this->table('labels', ['id' => true, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci', 'signed' => false])
            ->addColumn('org_id', 'integer', ['null' => true, 'signed' => false, 'comment' => 'Reserved for multi-tenant support'])
            ->addColumn('name', 'string', ['limit' => 60])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['name', 'org_id'], ['unique' => true])
            ->create();
    }
}
