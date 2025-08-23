<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateJobsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('jobs', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'signed' => false,
        ]);

        $table->addColumn('title', 'string', ['limit' => 150, 'null' => false])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('required_fields_json', 'json', ['null' => false])
              ->addColumn('must_have', 'json', ['null' => true])
              ->addColumn('nice_to_have', 'json', ['null' => true])
              ->addColumn('block_rules', 'json', ['null' => true])
              ->addColumn('heat_threshold', 'integer', ['default' => 70, 'null' => false])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addIndex(['title'])
              ->create();
    }
}
