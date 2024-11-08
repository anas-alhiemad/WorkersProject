<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;

class AdminPost extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $worker; 
    protected $post;
    public function __construct($worker,$post)
    {
        $this->worker = $worker;
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

   
   
    public function toArray(object $notifiable): array
    {
        return [
            "worker"=>$this->worker,
            "post"=>$this->post
        ];
    }
}
