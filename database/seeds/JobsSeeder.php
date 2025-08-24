<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class JobsSeeder extends AbstractSeed
{
    public function run(): void
    {
        $table = $this->table('jobs');
        $table->truncate();

        $data = [
            [
                'id' => 1,
                'title' => 'מפתח/ת Full-Stack בכיר/ה',
                'description' => 'דרוש/ה מפתח/ת Full-Stack עם נסיון של 5 שנים לפחות ב-PHP ו-React.',
                'required_fields_json' => '{"experience_years": "כמה שנות נסיון יש לך בפיתוח Full-Stack?", "core_skills": "מהן טכנולוגיות הליבה שלך (לדוגמה: PHP, React, Node.js)?", "salary_expectation": "מהן ציפיות השכר שלך?", "availability": "מתי תוכל/י להתחיל לעבוד?"}',
                'must_have' => '["PHP", "React", "MySQL"]',
                'nice_to_have' => '["Docker", "AWS", "TypeScript"]',
                'heat_threshold' => 75,
            ],
            [
                'id' => 2,
                'title' => 'מנהל/ת מוצר',
                'description' => 'חברת סטארטאפ בצמיחה מחפשת מנהל/ת מוצר להובלת קו מוצרים חדשני.',
                'required_fields_json' => '{"product_experience": "ספר/י על נסיונך בניהול מוצר.", "b2b_saas": "האם יש לך נסיון עם מוצרי B2B SaaS?", "methodologies": "עם אילו מתודולוגיות Agile עבדת?"}',
                'must_have' => '["ניהול מוצר", "Agile", "B2B"]',
                'nice_to_have' => '["עיצוב UX/UI", "JIRA", "SQL"]',
                'heat_threshold' => 70,
            ]
        ];

        $table->insert($data)->saveData();
    }
}
