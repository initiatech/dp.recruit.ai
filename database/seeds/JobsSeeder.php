<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class JobsSeeder extends AbstractSeed
{
    public function getDependencies(): array
    {
        return [];
    }

    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'title' => 'מפתח/ת Full-Stack בכיר/ה',
                'description' => 'דרוש/ה מפתח/ת Full-Stack עם נסיון של 5 שנים לפחות ב-PHP ו-React.',
                'required_fields_json' => json_encode([
                    'experience_years' => 'כמה שנות נסיון יש לך בפיתוח Full-Stack?',
                    'core_skills' => 'מהן טכנולוגיות הליבה שלך (לדוגמה: PHP, React, Node.js)?',
                    'salary_expectation' => 'מהן ציפיות השכר שלך?',
                    'availability' => 'מתי תוכל/י להתחיל לעבוד?'
                ]),
                'must_have' => json_encode(['PHP', 'React', 'MySQL']),
                'nice_to_have' => json_encode(['Docker', 'AWS', 'TypeScript']),
                'heat_threshold' => 75,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'title' => 'מנהל/ת מוצר',
                'description' => 'חברת סטארטאפ בצמיחה מחפשת מנהל/ת מוצר להובלת קו מוצרים חדשני.',
                'required_fields_json' => json_encode([
                    'product_experience' => 'ספר/י על נסיונך בניהול מוצר.',
                    'b2b_saas' => 'האם יש לך נסיון עם מוצרי B2B SaaS?',
                    'methodologies' => 'עם אילו מתודולוגיות Agile עבדת?'
                ]),
                'must_have' => json_encode(['ניהול מוצר', 'Agile', 'B2B']),
                'nice_to_have' => json_encode(['עיצוב UX/UI', 'JIRA', 'SQL']),
                'heat_threshold' => 70,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $jobs = $this->table('jobs');
        $jobs->truncate();
        $jobs->insert($data)->saveData();
    }
}
