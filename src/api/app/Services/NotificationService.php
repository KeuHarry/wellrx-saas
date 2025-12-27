<?php

namespace App\Services;

use App\Events\NewNotificationEvent;

class NotificationService
{
    public static function send(int $userId, string $title, string $message, string $type = 'info'): void
    {
        broadcast(new NewNotificationEvent([
            'userId' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]));
    }
}
