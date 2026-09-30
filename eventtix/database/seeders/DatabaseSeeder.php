<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin EventTix',
            'email' => 'admin@eventtix.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $organizer = User::create([
            'name' => 'Yanis Organisateur',
            'email' => 'organizer@eventtix.test',
            'password' => Hash::make('password'),
            'role' => 'organizer',
        ]);

        $user = User::create([
            'name' => 'Amel Participante',
            'email' => 'user@eventtix.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $events = [
            [
                'title' => 'Tech Conference 2026',
                'category' => 'Technologie',
                'location' => 'Alger, Algérie',
                'description' => "La plus grande conférence tech d'Algérie : IA, cloud et carrières IT, avec des speakers internationaux et des ateliers pratiques.",
                'start_date' => now()->addDays(30)->setTime(9, 0),
                'end_date' => now()->addDays(30)->setTime(18, 0),
                'capacity' => 300,
                'tickets' => [
                    ['name' => 'Standard', 'price' => 3000, 'quantity' => 200],
                    ['name' => 'VIP', 'price' => 8000, 'quantity' => 50],
                    ['name' => 'Étudiant', 'price' => 1000, 'quantity' => 50],
                ],
            ],
            [
                'title' => 'Festival de Musique Andalouse',
                'category' => 'Musique',
                'location' => 'Tizi Ouzou, Algérie',
                'description' => "Une soirée dédiée à la musique andalouse et chaâbi avec les meilleurs orchestres de la région.",
                'start_date' => now()->addDays(15)->setTime(19, 0),
                'end_date' => now()->addDays(15)->setTime(23, 0),
                'capacity' => 500,
                'tickets' => [
                    ['name' => 'Entrée générale', 'price' => 1500, 'quantity' => 400],
                    ['name' => 'Carré Or', 'price' => 4000, 'quantity' => 100],
                ],
            ],
            [
                'title' => 'Salon de l\'Entrepreneuriat',
                'category' => 'Business',
                'location' => 'Oran, Algérie',
                'description' => "Rencontrez des investisseurs, participez à des ateliers de pitch et développez votre réseau professionnel.",
                'start_date' => now()->addDays(45)->setTime(9, 0),
                'end_date' => now()->addDays(46)->setTime(17, 0),
                'capacity' => 200,
                'tickets' => [
                    ['name' => 'Accès 1 jour', 'price' => 2000, 'quantity' => 120],
                    ['name' => 'Pass 2 jours', 'price' => 3500, 'quantity' => 80],
                ],
            ],
        ];

        foreach ($events as $e) {
            $ticketTypes = $e['tickets'];
            unset($e['tickets']);

            $event = Event::create($e + ['organizer_id' => $organizer->id, 'status' => 'published']);

            foreach ($ticketTypes as $tt) {
                TicketType::create($tt + ['event_id' => $event->id]);
            }
        }

        $this->command->info('Comptes de démo :');
        $this->command->info('  admin@eventtix.test / password');
        $this->command->info('  organizer@eventtix.test / password');
        $this->command->info('  user@eventtix.test / password');
    }
}
