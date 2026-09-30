<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $event = $this->booking->event;

        return (new MailMessage)
            ->subject('Confirmation de réservation - '.$event->title)
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Votre réservation pour **'.$event->title.'** est confirmée.')
            ->line('Référence : '.$this->booking->booking_reference)
            ->line('Nombre de billets : '.$this->booking->quantity)
            ->line('Date : '.$event->start_date->format('d/m/Y à H:i'))
            ->line('Lieu : '.$event->location)
            ->action('Voir mes billets', route('bookings.show', $this->booking))
            ->line('Présentez le QR code de chaque billet à l\'entrée. Merci et à bientôt !');
    }
}
