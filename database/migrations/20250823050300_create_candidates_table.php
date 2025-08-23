<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCandidatesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('candidates', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        $table->addColumn('full_name', 'string', ['limit' => 150, 'null' => false])
              ->addColumn('email', 'string', ['limit' => 190, 'null' => true])
              ->addColumn('phone', 'string', ['limit' => 40, 'null' => true])
              ->addColumn('cv_text', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM, 'null' => true])
              ->addColumn('source', 'string', ['limit' => 100, 'null' => true])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addIndex(['email'])
              ->addIndex(['phone'])
              ->create();
    }
}
