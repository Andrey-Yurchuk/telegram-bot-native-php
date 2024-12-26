<?php

namespace App\Controllers;

use App\Bot\Bot;
use App\Bot\User;

class BotController
{
    private Bot $bot;
    private User $user;

    public function __construct($botToken)
    {
        $this->bot = new Bot($botToken);
        $this->user = new User();
    }

    public function handleWebhook($update): void
    {
        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $telegramId = $update['message']['from']['id'];
            $username = htmlspecialchars($update['message']['from']['username'] ?? 'unknown', ENT_QUOTES, 'UTF-8');
            $message = htmlspecialchars($update['message']['text'], ENT_QUOTES, 'UTF-8');

            $user = $this->user->getUserByTelegramId($telegramId);

            if (!$user) {
                $this->user->createUser($telegramId, $username);
                $responseMessage = "Привет, $username! Вы зарегистрированы с балансом $0.00.";
            } else {
                $responseMessage = "Привет, $username! Ваш текущий баланс: $" . number_format($user['balance'], 2);
            }

            if (is_numeric(str_replace(',', '.', $message))) {
                $amount = (float)str_replace(',', '.', $message);

                $newBalance = $user['balance'] + $amount;

                if ($newBalance < 0) {
                    $responseMessage = "Ошибка! На вашем счёте недостаточно средств для списания. Ваш баланс: $" . number_format($user['balance'], 2);
                } else {
                    $this->user->updateUserBalance($telegramId, $newBalance);
                    $responseMessage = "Ваш новый баланс: $" . number_format($newBalance, 2);
                }
            }

            $this->bot->sendMessage($chatId, $responseMessage);
        }
    }
}
