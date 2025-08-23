<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateHeatScoresTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('heat_scores', [
            'id' => true,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'signed' => false,
        ]);

        $table->addColumn('conversation_id', 'uuid', ['null' => false])
              ->addColumn('score', 'integer', ['null' => false])
              ->addColumn('breakdown_json', 'json', ['null' => false])
              ->addColumn('rubric_json', 'json', ['null' => true])
              ->addColumn('calculated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => ''])
              ->addForeignKey('conversation_id', 'conversations', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION', 'constraint' => 'fk_heat_scores_conv_id'])
              ->addIndex(['conversation_id'], ['unique' => true, 'name' => 'uniq_heat_once'])
              ->create();
    }
}
