<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateRecruiterNotesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('recruiter_notes', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'signed' => false,
        ]);

        $table->addColumn('conversation_id', 'string', ['limit' => 36, 'null' => false, 'collation' => 'utf8mb4_bin'])
              ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false])
              ->addColumn('note', 'text', ['null' => false])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addForeignKey('conversation_id', 'conversations', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE', 'constraint' => 'fk_notes_conv_id'])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE', 'constraint' => 'fk_notes_user_id'])
              ->addIndex(['conversation_id'])
              ->addIndex(['user_id'])
              ->create();
    }
}
