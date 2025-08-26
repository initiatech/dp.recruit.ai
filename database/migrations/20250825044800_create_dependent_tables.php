<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDependentTables extends AbstractMigration
{
    public function change(): void
    {
        // messages table
        $messages = $this->table('messages', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $messages->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('conversation_id', 'string', ['limit' => 36, 'null' => false, 'collation' => 'utf8mb4_bin'])
            ->addColumn('sender', 'enum', ['values' => ['candidate', 'ai', 'system']])
            ->addColumn('content_text', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM, 'null' => true])
            ->addColumn('content_audio_url', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('start_ms', 'integer', ['null' => true])
            ->addColumn('end_ms', 'integer', ['null' => true])
            ->addColumn('barge_in', 'boolean', ['default' => false])
            ->addColumn('confidence', 'decimal', ['precision' => 3, 'scale' => 2, 'null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('conversation_id', 'conversations', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addIndex(['conversation_id'])
            ->addIndex(['created_at'])
            ->create();

        // interview_progress table
        $progress = $this->table('interview_progress', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $progress->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('conversation_id', 'string', ['limit' => 36, 'null' => false, 'collation' => 'utf8mb4_bin'])
            ->addColumn('field_key', 'string', ['limit' => 100])
            ->addColumn('field_value', 'json', ['null' => true])
            ->addColumn('collected_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('conversation_id', 'conversations', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addIndex(['conversation_id', 'field_key'])
            ->create();

        // heat_scores table
        $heatScores = $this->table('heat_scores', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $heatScores->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('conversation_id', 'string', ['limit' => 36, 'null' => false, 'collation' => 'utf8mb4_bin'])
            ->addColumn('score', 'integer')
            ->addColumn('breakdown_json', 'json')
            ->addColumn('rubric_json', 'json', ['null' => true])
            ->addColumn('calculated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('conversation_id', 'conversations', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addIndex(['conversation_id'], ['unique' => true])
            ->create();

        // recruiter_notes table
        $notes = $this->table('recruiter_notes', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $notes->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('conversation_id', 'string', ['limit' => 36, 'null' => false, 'collation' => 'utf8mb4_bin'])
            ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('note', 'text')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('conversation_id', 'conversations', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addIndex(['conversation_id'])
            ->addIndex(['user_id'])
            ->create();

        // candidate_labels table
        $candLabels = $this->table('candidate_labels', ['id' => false, 'primary_key' => ['candidate_id', 'label_id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $candLabels->addColumn('candidate_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('label_id', 'integer', ['null' => false, 'signed' => false])
            ->addForeignKey('candidate_id', 'candidates', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('label_id', 'labels', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addIndex(['label_id'])
            ->create();

        // audit_logs table
        $audit = $this->table('audit_logs', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $audit->addColumn('id', 'integer', ['identity' => true, 'signed' => false])
            ->addColumn('actor_user_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('entity_type', 'string', ['limit' => 50])
            ->addColumn('entity_id', 'string', ['limit' => 64])
            ->addColumn('action', 'string', ['limit' => 50])
            ->addColumn('before_json', 'json', ['null' => true])
            ->addColumn('after_json', 'json', ['null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('actor_user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
            ->addIndex(['entity_type', 'entity_id'])
            ->addIndex(['actor_user_id'])
            ->addIndex(['created_at'])
            ->create();
    }
}
