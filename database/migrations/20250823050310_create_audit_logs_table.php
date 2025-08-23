<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAuditLogsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('audit_logs', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'signed' => false,
        ]);

        $table->addColumn('actor_user_id', 'integer', ['null' => true, 'signed' => false])
              ->addColumn('entity_type', 'string', ['limit' => 50, 'null' => false])
              ->addColumn('entity_id', 'string', ['limit' => 64, 'null' => false])
              ->addColumn('action', 'string', ['limit' => 50, 'null' => false])
              ->addColumn('before_json', 'json', ['null' => true])
              ->addColumn('after_json', 'json', ['null' => true])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addForeignKey('actor_user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE', 'constraint' => 'fk_audit_actor_id'])
              ->addIndex(['entity_type', 'entity_id'])
              ->addIndex(['actor_user_id'])
              ->addIndex(['created_at'])
              ->create();
    }
}
