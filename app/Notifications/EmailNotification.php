<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification
{
  use Queueable;

  private string $subject;
  private string|null $submitter_name;
  private array $answers;
  private int $toAdmin;

  /**
   * Create a new notification instance.
   */
  public function __construct($subject, $submitter_name, $answers, $toAdmin)
  {
    $this->subject = $subject;
    $this->submitter_name = $submitter_name;
    $this->answers = $answers;
    $this->toAdmin = $toAdmin;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail(object $notifiable): MailMessage
  {
    /*
    return (new MailMessage)
      ->line('The introduction to the notification.')
      ->action('Notification Action', url('/'))
      ->line('Thank you for using our application!');
    */

    $mailMessage = (new MailMessage)
      ->from(env('MAIL_FROM_ADDRESS'), config('app.name'))
      ->subject($this->subject);

    if ($this->toAdmin) {
      $mailMessage->line('---- Másolat egy frissen beérkezett kitöltött kérdőívről az admin részére ----');
    }

    $mailMessage->greeting('Tisztelt kérdőív kitöltő!')
      ->line('Az alábbiakban megtalálja a kitöltött kérdőív adatait:');

    if ($this->submitter_name) {
      $mailMessage->line('Neme: ' . $this->submitter_name);
    }

    foreach ($this->answers as $answer) {
      if (isset($answer[0], $answer[1])) {
        $mailMessage->line("**Kérdés:** {$answer[0]}");
        $mailMessage->line("**Válasz:** {$answer[1]}");
        $mailMessage->line('---');
      }
    }

    return $mailMessage
      ->line('Üdvözlettel,')
      ->salutation('Teszt Program Hivatal');

  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    return [
      //
    ];
  }
}
