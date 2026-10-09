<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pasien;
use App\Models\Obat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna per Role
        $users = [
            [
                'name' => 'Administrator Klinik',
                'email' => 'admin@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Dokter Budi (Umum)',
                'email' => 'dokter@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
            ],
            [
                'name' => 'Apoteker Siti',
                'email' => 'apoteker@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'apoteker',
            ],
            [
                'name' => 'Kasir Rina',
                'email' => 'kasir@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ],
            [
                'name' => 'Perawat Ani',
                'email' => 'perawat@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'perawat',
            ],
            [
                'name' => 'Resepsionis Joko',
                'email' => 'resepsionis@klinik.com',
                'password' => Hash::make('password'),
                'role' => 'resepsionis',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }

        // 2. Sample Data Pasien
        Pasien::create([
            'no_rm' => 'RM-0001',
            'nama' => 'Budi Santoso',
            'tgl_lahir' => '1990-05-15',
            'jenis_kelamin' => 'L',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 12, Jakarta',
        ]);

        Pasien::create([
            'no_rm' => 'RM-0002',
            'nama' => 'Siti Rahma',
            'tgl_lahir' => '1995-08-20',
            'jenis_kelamin' => 'P',
            'no_telp' => '089876543210',
            'alamat' => 'Jl. Sudirman No. 45, Jakarta',
        ]);

        // 3. Sample Data Obat
        Obat::create([
            'kode_obat' => 'OBT-001',
            'nama_obat' => 'Paracetamol 500mg',
            'harga' => 10000,
            'stok' => 100,
        ]);

        Obat::create([
            'kode_obat' => 'OBT-002',
            'nama_obat' => 'Amoxicillin 500mg',
            'harga' => 25000,
            'stok' => 50,
        ]);

        Obat::create([
            'kode_obat' => 'OBT-003',
            'nama_obat' => 'Vitamin C 1000mg',
            'harga' => 15000,
            'stok' => 80,
        ]);
    }
}
