<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateInterviewProgressTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('interview_progress', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'signed' => false,
        ]);

        $table->addColumn('conversation_id', 'string', ['limit' => 36, 'null' => false, 'collation' => 'utf8mb4_bin'])
              ->addColumn('field_key', 'string', ['limit' => 100, 'null' => false])
              ->addColumn('field_value', 'json', ['null' => true])
              ->addColumn('collected_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addForeignKey('conversation_id', 'conversations', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE', 'constraint' => 'fk_progress_conv_id'])
              ->addIndex(['conversation_id', 'field_key'])
              ->create();
    }
}
