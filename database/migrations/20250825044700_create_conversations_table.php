<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateConversationsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('conversations', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);
        $table->addColumn('id', 'string', ['limit' => 36, 'null' => false, 'collation' => 'utf8mb4_bin'])
              ->addColumn('candidate_id', 'integer', ['null' => false, 'signed' => false])
              ->addColumn('job_id', 'integer', ['null' => false, 'signed' => false])
              ->addColumn('status', 'enum', [
                  'values' => ['new', 'in_interview', 'completed', 'accepted', 'rejected', 'more_info'],
                  'default' => 'new',
              ])
              ->addColumn('started_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('closed_at', 'datetime', ['null' => true])
              ->addForeignKey('candidate_id', 'candidates', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->addForeignKey('job_id', 'jobs', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->addIndex(['candidate_id'])
              ->addIndex(['job_id'])
              ->addIndex(['status'])
              ->addIndex(['started_at'])
              ->create();
    }
}
