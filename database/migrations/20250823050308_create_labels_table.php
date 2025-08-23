<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLabelsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('labels', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        $table->addColumn('org_id', 'integer', ['null' => true, 'comment' => 'Reserved for multi-tenant support'])
              ->addColumn('name', 'string', ['limit' => 60, 'null' => false])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addIndex(['name', 'org_id'], ['unique' => true, 'name' => 'uniq_label_name_org'])
              ->create();
    }
}
