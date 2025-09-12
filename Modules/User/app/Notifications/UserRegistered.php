<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct() {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('سلام ' . ($notifiable->name ?? 'کاربر عزیز'))
            ->line('به ' . config('app.name') . ' خوش آمدید.')
            ->action('ورود به حساب کاربری', url('/login'))
            ->line('از اینکه ما را انتخاب کردید سپاسگزاریم.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'ثبت‌نام موفق',
            'message' => 'کاربر ' . ($notifiable->name ?? $notifiable->email) . ' با موفقیت ثبت‌نام کرد.',
            'user_id' => $notifiable->id,
        ];
    }
}
