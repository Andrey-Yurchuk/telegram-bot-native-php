<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\BotController;
use App\Utils\EnvLoader;

EnvLoader::load();

$botToken = getenv('TELEGRAM_BOT_TOKEN');

if (!$botToken) {
    die('Ошибка: TELEGRAM_BOT_TOKEN не найден в .env');
}

$update = json_decode(file_get_contents("php://input"), TRUE);

$controller = new BotController($botToken);
$controller->handleWebhook($update);
