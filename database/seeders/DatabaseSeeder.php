<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\SpaService;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── ADMIN ────────────────────────────────────────────────
        User::create([
            'name'     => 'Admin Serenity',
            'email'    => 'admin@serenityspace.id',
            'phone'    => '081200000001',
            'role'     => 'admin',
            'password' => Hash::make('admin123'),
            'avatar'   => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&q=80&w=250',
        ]);

        // ── CUSTOMERS ────────────────────────────────────────────
        $customers = [
            ['name' => 'Rina Kartika',   'phone' => '081234567890', 'email' => 'rina@mail.com'],
            ['name' => 'Budi Santoso',   'phone' => '082345678901', 'email' => 'budi@mail.com'],
            ['name' => 'Siti Rahayu',    'phone' => '083456789012', 'email' => 'siti@mail.com'],
            ['name' => 'Dewi Lestari',   'phone' => '084567890123', 'email' => 'dewi@mail.com'],
            ['name' => 'Ahmad Fauzi',    'phone' => '085678901234', 'email' => 'ahmad@mail.com'],
        ];
        foreach ($customers as $c) {
            User::create([
                'name'     => $c['name'],
                'email'    => $c['email'],
                'phone'    => $c['phone'],
                'role'     => 'customer',
                'password' => Hash::make('password'),
                'avatar'   => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250',
            ]);
        }

        // ── SPA SERVICES (F55 MEN RELAXATION & MASSAGE CENTER) ──
        $services = [
            [
                'name'          => 'Balinese Massage',
                'category'      => 'Massage',
                'description'   => 'A traditional massage from Bali that combines gentle to firm pressure, long strokes, and aromatherapy oils to relieve muscle tension, improve blood circulation, and promote deep relaxation.',
                'duration'      => 60,
                'price'         => 150,
                'pricing_tiers' => ['30' => 75, '60' => 150, '90' => 225, '120' => 300, '150' => 375, '180' => 450, '210' => 525, '240' => 600],
                'rating'        => 4.9,
                'image_url'     => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Gentle to firm pressure', 'Relieve muscle tension', 'Improve blood circulation', 'Deep relaxation'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Thai Massage',
                'category'      => 'Massage',
                'description'   => 'An ancient Thai therapy that combines acupressure, stretching, and assisted yoga postures to improve flexibility, relieve muscle tension, and balance energy flow.',
                'duration'      => 60,
                'price'         => 150,
                'pricing_tiers' => ['30' => 75, '60' => 150, '90' => 225, '120' => 300, '150' => 375, '180' => 450, '210' => 525, '240' => 600],
                'rating'        => 4.8,
                'image_url'     => 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Ancient Thai therapy', 'Acupressure & stretching', 'Improve flexibility', 'Balance energy flow'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Sports Massage',
                'category'      => 'Massage',
                'description'   => 'Designed for active individuals and athletes to reduce muscle soreness, prevent injuries, improve performance, and speed up recovery.',
                'duration'      => 60,
                'price'         => 150,
                'pricing_tiers' => ['30' => 75, '60' => 150, '90' => 225, '120' => 300, '150' => 375, '180' => 450, '210' => 525, '240' => 600],
                'rating'        => 4.9,
                'image_url'     => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['For active individuals', 'Reduce muscle soreness', 'Prevent injuries', 'Speed up recovery'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Shiatsu Massage',
                'category'      => 'Massage',
                'description'   => 'A Japanese massage technique using finger pressure on specific points to restore energy flow, reduce stress, and promote overall well-being.',
                'duration'      => 60,
                'price'         => 150,
                'pricing_tiers' => ['30' => 75, '60' => 150, '90' => 225, '120' => 300, '150' => 375, '180' => 450, '210' => 525, '240' => 600],
                'rating'        => 4.8,
                'image_url'     => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Japanese technique', 'Finger pressure points', 'Restore energy flow', 'Reduce stress'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Massage with Ice Pool',
                'category'      => 'Special Therapy',
                'description'   => 'A refreshing therapy that combines therapeutic massage with an ice pool immersion to reduce inflammation, relieve muscle fatigue, and rejuvenate the body.',
                'duration'      => 60,
                'price'         => 200,
                'pricing_tiers' => ['30' => 100, '60' => 200, '90' => 300, '120' => 400, '150' => 500, '180' => 600, '210' => 700, '240' => 800],
                'rating'        => 4.9,
                'image_url'     => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Therapeutic massage', 'Ice pool immersion', 'Reduce inflammation', 'Rejuvenate the body'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Massage with Cupping',
                'category'      => 'Special Therapy',
                'description'   => 'This therapy uses suction cups to stimulate blood flow, release deep muscle tension, detoxify the body, and promote healing.',
                'duration'      => 60,
                'price'         => 200,
                'pricing_tiers' => ['30' => 100, '60' => 200, '90' => 300, '120' => 400, '150' => 500, '180' => 600, '210' => 700, '240' => 800],
                'rating'        => 4.8,
                'image_url'     => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Suction cup therapy', 'Stimulate blood flow', 'Release deep tension', 'Detoxify the body'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Massage with Hot Stone',
                'category'      => 'Special Therapy',
                'description'   => 'Warm volcanic stones are placed on specific points and used in massage to relax muscles, improve circulation, and induce deep relaxation.',
                'duration'      => 60,
                'price'         => 200,
                'pricing_tiers' => ['30' => 100, '60' => 200, '90' => 300, '120' => 400, '150' => 500, '180' => 600, '210' => 700, '240' => 800],
                'rating'        => 4.9,
                'image_url'     => 'https://images.unsplash.com/photo-1559599101-f09722fb4948?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Warm volcanic stones', 'Relax deep muscles', 'Improve circulation', 'Induce deep relaxation'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Leg Massage',
                'category'      => 'Targeted Massage',
                'description'   => 'Focuses on the legs and feet to improve circulation, relieve swelling, reduce muscle tension, and promote overall relaxation.',
                'duration'      => 30,
                'price'         => 70,
                'pricing_tiers' => ['30' => 70, '60' => 100, '90' => 150, '120' => 200, '150' => 250, '180' => 300, '210' => 350, '240' => 400],
                'rating'        => 4.7,
                'image_url'     => 'https://images.unsplash.com/photo-1519824145371-296894a0daa9?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Focus on legs & feet', 'Improve circulation', 'Relieve swelling', 'Reduce muscle tension'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Back Massage',
                'category'      => 'Targeted Massage',
                'description'   => 'Targets the back, shoulders, and neck to relieve pain, reduce stiffness, and improve posture.',
                'duration'      => 30,
                'price'         => 80,
                'pricing_tiers' => ['30' => 80, '60' => 160, '90' => 240, '120' => 320, '150' => 400, '180' => 480, '210' => 560, '240' => 640],
                'rating'        => 4.8,
                'image_url'     => 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Back, shoulders & neck', 'Relieve back pain', 'Reduce stiffness', 'Improve posture'],
                'is_active'     => true,
            ],
            [
                'name'          => 'Only Ice Bath',
                'category'      => 'Hydrotherapy',
                'description'   => 'Ice bath immersion helps reduce muscle inflammation, speed up recovery, boost circulation, and refresh the body and mind.',
                'duration'      => 30,
                'price'         => 50,
                'pricing_tiers' => ['30' => 50, '60' => 100, '90' => 150, '120' => 200, '150' => 250, '180' => 300, '210' => 350, '240' => 400],
                'rating'        => 4.9,
                'image_url'     => 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?auto=format&fit=crop&q=80&w=800',
                'benefits'      => ['Ice bath immersion', 'Reduce muscle inflammation', 'Speed up recovery', 'Boost circulation'],
                'is_active'     => true,
            ],
        ];

        $createdServices = [];
        foreach ($services as $s) {
            $createdServices[] = SpaService::create($s);
        }

        // ── THERAPISTS ───────────────────────────────────────────
        $therapists = [
            [
                'name'       => 'Dewi Sartika',
                'speciality' => 'Swedish & Balinese Massage',
                'experience' => 8,
                'rating'     => 4.9,
                'phone'      => '087700000001',
                'avatar'     => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=250',
                'is_active'  => true,
            ],
            [
                'name'       => 'Sri Mulyani',
                'speciality' => 'Aromatherapy & Facial',
                'experience' => 5,
                'rating'     => 4.8,
                'phone'      => '087700000002',
                'avatar'     => 'https://images.unsplash.com/photo-1584361853901-dd1904bb7987?auto=format&fit=crop&q=80&w=250',
                'is_active'  => true,
            ],
            [
                'name'       => 'Ratna Dewi',
                'speciality' => 'Deep Tissue & Hot Stone',
                'experience' => 10,
                'rating'     => 5.0,
                'phone'      => '087700000003',
                'avatar'     => 'https://images.unsplash.com/photo-1573497019236-17f8177b81e8?auto=format&fit=crop&q=80&w=250',
                'is_active'  => true,
            ],
            [
                'name'       => 'Ayu Lestari',
                'speciality' => 'Body Treatment & Scrub',
                'experience' => 6,
                'rating'     => 4.7,
                'phone'      => '087700000004',
                'avatar'     => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&q=80&w=250',
                'is_active'  => true,
            ],
            [
                'name'       => 'Putri Indah',
                'speciality' => 'Couple Spa & Romantic Package',
                'experience' => 4,
                'rating'     => 4.9,
                'phone'      => '087700000005',
                'avatar'     => 'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&q=80&w=250',
                'is_active'  => true,
            ],
        ];

        $createdTherapists = [];
        foreach ($therapists as $t) {
            $createdTherapists[] = Therapist::create($t);
        }

        // ── BOOKINGS ─────────────────────────────────────────────
        $users = User::where('role', 'customer')->get();
        $statuses = ['completed', 'completed', 'confirmed', 'in_progress', 'cancelled'];
        $times = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
        $payments = ['QRIS / E-Wallet', 'Transfer Bank', 'Tunai'];

        foreach ($users as $i => $user) {
            $service   = $createdServices[$i % count($createdServices)];
            $therapist = $createdTherapists[$i % count($createdTherapists)];
            $duration  = $service->duration;
            $status    = $statuses[$i % count($statuses)];
            $date      = now()->subDays(rand(1, 30))->format('Y-m-d');

            Booking::create([
                'booking_code'   => 'SPA-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id'        => $user->id,
                'customer_name'  => $user->name,
                'customer_phone' => $user->phone,
                'spa_service_id' => $service->id,
                'service_name'   => $service->name,
                'therapist_id'   => $therapist->id,
                'therapist_name' => $therapist->name,
                'booking_date'   => $date,
                'booking_time'   => $times[$i % count($times)],
                'duration'       => $duration,
                'price'          => $service->price,
                'payment_method' => $payments[$i % count($payments)],
                'status'         => $status,
            ]);
        }

        // Extra bookings for dashboard variety
        foreach (range(1, 5) as $j) {
            $user      = $users->random();
            $service   = $createdServices[array_rand($createdServices)];
            $therapist = $createdTherapists[array_rand($createdTherapists)];
            Booking::create([
                'booking_code'   => 'SPA-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id'        => $user->id,
                'customer_name'  => $user->name,
                'customer_phone' => $user->phone,
                'spa_service_id' => $service->id,
                'service_name'   => $service->name,
                'therapist_id'   => $therapist->id,
                'therapist_name' => $therapist->name,
                'booking_date'   => now()->addDays($j)->format('Y-m-d'),
                'booking_time'   => $times[$j % count($times)],
                'duration'       => $service->duration,
                'price'          => $service->price,
                'payment_method' => $payments[$j % count($payments)],
                'status'         => 'confirmed',
            ]);
        }
    }
}
