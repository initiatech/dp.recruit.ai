<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CandidatesSeeder extends AbstractSeed
{
    public function run(): void
    {
        $table = $this->table('candidates');
        $table->truncate();

        $data = [
            [
                'id' => 1,
                'full_name' => 'ישראל ישראלי',
                'email' => 'israel@example.com',
                'phone' => '050-1234567',
                'cv_text' => 'קורות חיים לדוגמה עבור ישראל ישראלי, מפתח תוכנה עם נסיון רב.',
                'source' => 'LinkedIn',
            ],
            [
                'id' => 2,
                'full_name' => 'משה כהן',
                'email' => 'moshe@example.com',
                'phone' => '052-7654321',
                'cv_text' => 'משה כהן, מנהל מוצר מנוסה.',
                'source' => 'אתר החברה',
            ],
            [
                'id' => 3,
                'full_name' => 'דנה לוי',
                'email' => 'dana@example.com',
                'phone' => '054-1122334',
                'cv_text' => null,
                'source' => 'חבר מביא חבר',
            ],
        ];

        $table->insert($data)->saveData();
    }
}
