<?php

namespace App\Bot;

use App\Database\Database;
use PDO;
class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function getUserByTelegramId($telegramId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE telegram_id = :telegram_id");
        $stmt->execute(['telegram_id' => $telegramId]);
        return $stmt->fetch();
    }

    public function createUser($telegramId, $username): void
    {
        $stmt = $this->db->prepare("INSERT INTO users (telegram_id, username, balance) VALUES (:telegram_id, :username, :balance)");
        $stmt->execute([
            'telegram_id' => $telegramId,
            'username' => $username,
            'balance' => 0.00
        ]);
    }

    public function updateUserBalance($telegramId, $newBalance): void
    {
        $stmt = $this->db->prepare("UPDATE users SET balance = :balance WHERE telegram_id = :telegram_id");
        $stmt->execute([
            'balance' => $newBalance,
            'telegram_id' => $telegramId
        ]);
    }
}
