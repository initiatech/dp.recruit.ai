<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMessagesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('messages', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        $table->addColumn('conversation_id', 'char', ['limit' => 36, 'null' => false])
              ->addColumn('sender', 'enum', ['values' => ['candidate', 'ai', 'system'], 'null' => false])
              ->addColumn('content_text', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM, 'null' => true])
              ->addColumn('content_audio_url', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('start_ms', 'integer', ['null' => true])
              ->addColumn('end_ms', 'integer', ['null' => true])
              ->addColumn('barge_in', 'boolean', ['default' => false, 'null' => false])
              ->addColumn('confidence', 'decimal', ['precision' => 3, 'scale' => 2, 'null' => true])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addForeignKey('conversation_id', 'conversations', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION', 'constraint' => 'fk_messages_conv_id'])
              ->addIndex(['conversation_id'])
              ->addIndex(['created_at'])
              ->create();
    }
}
