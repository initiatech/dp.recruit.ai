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
        $conv_id_1 = 'a1b2c3d4-e5f6-7890-1234-567890abcdef';
        $conv_id_2 = 'fedcba98-7654-3210-fedc-ba9876543210';

        // Conversations
        $conversationsData = [
            [
                'id' => $conv_id_1,
                'candidate_id' => 1, // ישראל ישראלי
                'job_id' => 1,       // מפתח Full-Stack
                'status' => 'in_interview',
                'started_at' => date('Y-m-d H:i:s'),
                'closed_at' => null,
            ],
            [
                'id' => $conv_id_2,
                'candidate_id' => 2, // משה כהן
                'job_id' => 2,       // מנהל מוצר
                'status' => 'new',
                'started_at' => date('Y-m-d H:i:s'),
                'closed_at' => null,
            ],
        ];
        $this->table('conversations')->truncate();
        $this->table('conversations')->insert($conversationsData)->saveData();

        // Messages
        $messagesData = [
            // Conversation 1
            [
                'conversation_id' => $conv_id_1,
                'sender' => 'ai',
                'content_text' => 'שלום ישראל, ברוך הבא לראיון. ספר לי קצת על הנסיון שלך.',
                'created_at' => date('Y-m-d H:i:s', time() - 60),
            ],
            [
                'conversation_id' => $conv_id_1,
                'sender' => 'candidate',
                'content_text' => 'בטח, אני מפתח כבר 10 שנים, התמקדתי בעיקר ב-PHP ובשנים האחרונות גם ב-React.',
                'created_at' => date('Y-m-d H:i:s', time() - 30),
            ],
             [
                'conversation_id' => $conv_id_1,
                'sender' => 'ai',
                'content_text' => 'מצוין, תודה. כמה שנות נסיון יש לך בפיתוח Full-Stack?',
                'created_at' => date('Y-m-d H:i:s', time() - 10),
            ],
            // Conversation 2
            [
                'conversation_id' => $conv_id_2,
                'sender' => 'ai',
                'content_text' => 'היי משה, תודה שהצטרפת. מה משך אותך במשרה שלנו?',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('messages')->truncate();
        $this->table('messages')->insert($messagesData)->saveData();
    }
}
