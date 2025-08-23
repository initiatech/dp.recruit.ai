<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Message;
use App\Utils\Database;
use PDO;

class MessageRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(Message &$message): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO messages (conversation_id, sender, content_text, content_audio_url, start_ms, end_ms, barge_in, confidence)
             VALUES (:conversation_id, :sender, :content_text, :content_audio_url, :start_ms, :end_ms, :barge_in, :confidence)"
        );

        $success = $stmt->execute([
            'conversation_id' => $message->conversation_id,
            'sender' => $message->sender,
            'content_text' => $message->content_text,
            'content_audio_url' => $message->content_audio_url,
            'start_ms' => $message->start_ms,
            'end_ms' => $message->end_ms,
            'barge_in' => $message->barge_in ? 1 : 0,
            'confidence' => $message->confidence,
        ]);

        if ($success) {
            $message->id = (int)$this->db->lastInsertId();
        }

        return $success;
    }

    /**
     * @return Message[]
     */
    public function findByConversation(string $conversationId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE conversation_id = :conversation_id ORDER BY created_at ASC");
        $stmt->execute(['conversation_id' => $conversationId]);

        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = $this->hydrate($row);
        }

        return $messages;
    }

    private function hydrate(array $data): Message
    {
        $message = new Message();
        $message->id = (int)$data['id'];
        $message->conversation_id = $data['conversation_id'];
        $message->sender = $data['sender'];
        $message->content_text = $data['content_text'];
        $message->content_audio_url = $data['content_audio_url'];
        $message->start_ms = $data['start_ms'] ? (int)$data['start_ms'] : null;
        $message->end_ms = $data['end_ms'] ? (int)$data['end_ms'] : null;
        $message->barge_in = (bool)$data['barge_in'];
        $message->confidence = $data['confidence'] ? (float)$data['confidence'] : null;
        $message->created_at = $data['created_at'];

        return $message;
    }
}
