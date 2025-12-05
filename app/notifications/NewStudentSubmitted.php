<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewStudentSubmitted extends Notification
{
    use Queueable;

    protected $tor;
    protected $user;

    public function __construct($tor, $user)
    {
        $this->tor = $tor;
        $this->user = $user;

        // 🟦 Log the notification creation details
        Log::info("📩 NewStudentSubmitted Notification Created", [
            'student_name' => "{$user->first_name} {$user->last_name}",
            'student_email' => $user->email,
            'student_course' => $user->course,
            'tor_id' => $tor->id,
        ]);
    }

    public function via($notifiable)
    {
        // 🟦 Log who the notification is being sent to
        Log::info("📨 Notification via(database) for admin", [
            'admin_id' => $notifiable->id,
            'admin_name' => "{$notifiable->first_name} {$notifiable->last_name}",
            'admin_course' => $notifiable->course,
        ]);

        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // 🟦 Log database payload
        Log::info("🗄️ Writing notification to database", [
            'for_admin_id' => $notifiable->id,
            'tor_id' => $this->tor->id,
            'student_id' => $this->user->id,
        ]);

        return [
            'title' => 'New Student Request Submitted',
            'message' => "{$this->user->first_name} {$this->user->last_name} submitted a request for advising.",
            'tor_id' => $this->tor->id,
            'user_id' => $this->user->id,
            'course' => $this->user->course,
        ];
    }
}
