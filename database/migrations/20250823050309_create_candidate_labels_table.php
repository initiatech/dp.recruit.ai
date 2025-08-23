<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCandidateLabelsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('candidate_labels', [
            'id' => false,
            'primary_key' => ['candidate_id', 'label_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        $table->addColumn('candidate_id', 'integer', ['null' => false])
              ->addColumn('label_id', 'integer', ['null' => false])
              ->addForeignKey('candidate_id', 'candidates', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION', 'constraint' => 'fk_cand_label_cand_id'])
              ->addForeignKey('label_id', 'labels', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION', 'constraint' => 'fk_cand_label_label_id'])
              ->addIndex(['label_id']) // Also index the other part of the key
              ->create();
    }
}
