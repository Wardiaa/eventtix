<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function makeEvent(array $attrs = []): Event
    {
        $organizer = User::create([
            'name' => 'Organizer',
            'email' => 'organizer+'.uniqid().'@test.local',
            'password' => bcrypt('password'),
            'role' => 'organizer',
        ]);

        return Event::create(array_merge([
            'organizer_id' => $organizer->id,
            'title' => 'Tech Conference',
            'description' => 'A great conference.',
            'category' => 'Technologie',
            'location' => 'Alger',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(10)->addHours(5),
            'capacity' => 100,
            'status' => 'published',
        ], $attrs));
    }

    protected function makeTicketType(Event $event, array $attrs = []): TicketType
    {
        return TicketType::create(array_merge([
            'event_id' => $event->id,
            'name' => 'Standard',
            'price' => 1000,
            'quantity' => 10,
            'quantity_sold' => 0,
        ], $attrs));
    }

    protected function makeUser(): User
    {
        return User::create([
            'name' => 'Amel',
            'email' => 'user+'.uniqid().'@test.local',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }

    public function test_user_can_book_an_available_ticket(): void
    {
        Notification::fake();

        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 2,
        ]);

        $booking = Booking::first();

        $response->assertRedirect(route('bookings.show', $booking));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', ['event_id' => $event->id, 'quantity' => 2, 'status' => 'confirmed']);
        $this->assertEquals(2, $ticketType->fresh()->quantity_sold);
        $this->assertCount(2, $booking->tickets);
    }

    public function test_booking_fails_with_friendly_message_when_sales_not_started(): void
    {
        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event, ['sales_start' => now()->addDay()]);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', "La vente de ce billet n'a pas encore commencé.");
        $this->assertDatabaseCount('bookings', 0);
        $this->assertEquals(0, $ticketType->fresh()->quantity_sold);
    }

    public function test_booking_fails_with_friendly_message_when_sales_ended(): void
    {
        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event, ['sales_end' => now()->subDay()]);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHas('error', 'La vente de ce billet est terminée.');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_fails_with_friendly_message_when_sold_out(): void
    {
        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event, ['quantity' => 5, 'quantity_sold' => 5]);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHas('error', 'Ce type de billet est complet.');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_fails_with_friendly_message_when_quantity_exceeds_availability(): void
    {
        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event, ['quantity' => 5, 'quantity_sold' => 2]); // 3 available
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 5,
        ]);

        $response->assertSessionHas('error', 'Il ne reste que 3 billet(s) disponible(s) pour ce type de billet.');
        $this->assertDatabaseCount('bookings', 0);
        $this->assertEquals(2, $ticketType->fresh()->quantity_sold);
    }

    public function test_booking_is_rejected_as_validation_error_when_quantity_is_invalid(): void
    {
        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 0,
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_is_rejected_when_ticket_type_belongs_to_another_event(): void
    {
        $eventA = $this->makeEvent(['title' => 'Event A']);
        $eventB = $this->makeEvent(['title' => 'Event B']);
        $ticketTypeOfB = $this->makeTicketType($eventB);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('bookings.store', $eventA), [
            'ticket_type_id' => $ticketTypeOfB->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors('ticket_type_id');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_is_rejected_for_a_cancelled_event(): void
    {
        $event = $this->makeEvent(['status' => 'cancelled']);
        $ticketType = $this->makeTicketType($event);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHas('error', "Cet événement n'est plus disponible à la réservation.");
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_succeeds_even_when_notification_fails(): void
    {
        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event);
        $user = $this->makeUser();

        // Simulate a transport-level failure (e.g. SMTP host unreachable)
        // without needing a real mail server.
        Notification::shouldReceive('send')->once()->andThrow(new \RuntimeException('Connection could not be established with host "mailpit:1025"'));

        $response = $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);

        $booking = Booking::first();

        $response->assertRedirect(route('bookings.show', $booking));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);
    }

    public function test_user_cannot_view_another_users_booking(): void
    {
        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event);
        $owner = $this->makeUser();
        $stranger = $this->makeUser();

        Notification::fake();
        $this->actingAs($owner)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);
        $booking = Booking::first();

        $response = $this->actingAs($stranger)->get(route('bookings.show', $booking));

        $response->assertStatus(403);
    }

    public function test_nonexistent_event_returns_404(): void
    {
        $response = $this->get('/events/this-slug-does-not-exist');

        $response->assertStatus(404);
    }

    public function test_cancellation_restores_availability_and_succeeds(): void
    {
        Notification::fake();
        $event = $this->makeEvent();
        $ticketType = $this->makeTicketType($event);
        $user = $this->makeUser();

        $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 2,
        ]);
        $booking = Booking::first();
        $this->assertEquals(2, $ticketType->fresh()->quantity_sold);

        $response = $this->actingAs($user)->post(route('bookings.cancel', $booking));

        $response->assertSessionHas('success');
        $this->assertEquals('cancelled', $booking->fresh()->status);
        $this->assertEquals(0, $ticketType->fresh()->quantity_sold);
    }

    public function test_cancellation_is_rejected_with_friendly_message_when_event_already_started(): void
    {
        Notification::fake();
        $event = $this->makeEvent(['start_date' => now()->subDay(), 'end_date' => now()->addDay()]);
        $ticketType = $this->makeTicketType($event);
        $user = $this->makeUser();

        $this->actingAs($user)->post(route('bookings.store', $event), [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);
        $booking = Booking::first();

        $response = $this->actingAs($user)->post(route('bookings.cancel', $booking));

        $response->assertSessionHas('error', 'Cette réservation ne peut plus être annulée.');
        $this->assertEquals('confirmed', $booking->fresh()->status);
        $this->assertEquals(1, $ticketType->fresh()->quantity_sold);
    }
}
