<?php

namespace Database\Seeders;

use App\Models\QrCode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QrCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->warn('No users found. Please create a user first.');

            return;
        }

        $qrCodes = [
            [
                'name' => '🐱 Gatos Haciendo Parkour',
                'uri' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ],
            [
                'name' => '🎮 El Juego más Adictivo del Mundo',
                'uri' => 'https://www.windows93.net/',
            ],
            [
                'name' => '🦆 Pato Infinito',
                'uri' => 'https://ducksarethebest.com/',
            ],
            [
                'name' => '🎵 Música Chill para Trabajar',
                'uri' => 'https://lofi.cafe/',
            ],
            [
                'name' => '🌌 Viaje al Espacio Profundo',
                'uri' => 'https://stars.chromeexperiments.com/',
            ],
            [
                'name' => '🐶 Perrito Virtual',
                'uri' => 'https://pomeranian.pet/',
            ],
            [
                'name' => '🎨 Arte Generativo Relajante',
                'uri' => 'https://www.ashortjourney.com/',
            ],
            [
                'name' => '🍕 Simulador de Pizza',
                'uri' => 'https://pizzagame.onrender.com/',
            ],
        ];

        foreach ($qrCodes as $qrData) {
            QrCode::create([
                'uuid' => (string) Str::uuid(),
                'name' => $qrData['name'],
                'uri' => $qrData['uri'],
                'user_id' => $user->id,
                'is_active' => true,
            ]);
        }

        $this->command->info('✨ Created '.count($qrCodes).' fun QR codes!');
    }
}
