<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class ConversationsAndMessagesSeeder extends AbstractSeed
{
    public function getDependencies(): array
    {
        return [
            'UsersSeeder',
            'JobsSeeder',
            'CandidatesSeeder',
        ];
    }

    public function run(): void
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');

        $convTable = $this->table('conversations');
        $convTable->truncate();
        $conversationsData = [
            [
                'id' => 'a1b2c3d4-e5f6-7890-1234-567890abcdef',
                'candidate_id' => 1, // ישראל ישראלי
                'job_id' => 1,       // מפתח Full-Stack
                'status' => 'in_interview',
            ],
            [
                'id' => 'fedcba98-7654-3210-fedc-ba9876543210',
                'candidate_id' => 2, // משה כהן
                'job_id' => 2,       // מנהל מוצר
                'status' => 'new',
            ],
        ];
        $convTable->insert($conversationsData)->saveData();

        $msgTable = $this->table('messages');
        $msgTable->truncate();
        $messagesData = [
            [
                'conversation_id' => 'a1b2c3d4-e5f6-7890-1234-567890abcdef',
                'sender' => 'ai',
                'content_text' => 'שלום ישראל, ברוך הבא לראיון. ספר לי קצת על הנסיון שלך.',
            ],
            [
                'conversation_id' => 'a1b2c3d4-e5f6-7890-1234-567890abcdef',
                'sender' => 'candidate',
                'content_text' => 'בטח, אני מפתח כבר 10 שנים, התמקדתי בעיקר ב-PHP ובשנים האחרונות גם ב-React.',
            ],
             [
                'conversation_id' => 'a1b2c3d4-e5f6-7890-1234-567890abcdef',
                'sender' => 'ai',
                'content_text' => 'מצוין, תודה. כמה שנות נסיון יש לך בפיתוח Full-Stack?',
            ],
            [
                'conversation_id' => 'fedcba98-7654-3210-fedc-ba9876543210',
                'sender' => 'ai',
                'content_text' => 'היי משה, תודה שהצטרפת. מה משך אותך במשרה שלנו?',
            ],
        ];
        $msgTable->insert($messagesData)->saveData();

        $this->execute('SET FOREIGN_KEY_CHECKS=1');
    }
}
