<?php

namespace App\Bot;

use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;

class Bot
{
    private Client $client;

    public function __construct($token)
    {
        $this->client = new Client($token);
    }

    public function sendMessage($chatId, $message): void
    {
        try {
            $this->client->sendMessage($chatId, $message);
        } catch (Exception $e) {
            error_log('Ошибка отправки сообщения: ' . $e->getMessage());
        }
    }
}
