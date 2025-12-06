<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin user
        $admin = User::create([
            'name' => 'Admin Kaprodi',
            'email' => 'admin@university.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Dosen users with profiles
        $dosen1 = User::create([
            'name' => 'Dr. Ahmad Fauzi',
            'email' => 'ahmad.fauzi@university.ac.id',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        \App\Models\Lecturer::create([
            'user_id' => $dosen1->id,
            'nip' => '198501012010121001',
            'expertise' => 'Machine Learning, Artificial Intelligence, Data Mining',
            'specialization' => 'Artificial Intelligence',
            'bio' => 'Dosen dengan keahlian di bidang AI dan Machine Learning dengan pengalaman 10+ tahun',
            'quota' => 5,
            'used_quota' => 0,
        ]);

        $dosen2 = User::create([
            'name' => 'Dr. Siti Rahma',
            'email' => 'siti.rahma@university.ac.id',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        \App\Models\Lecturer::create([
            'user_id' => $dosen2->id,
            'nip' => '198703152011012002',
            'expertise' => 'Web Development, Mobile App Development, Cloud Computing',
            'specialization' => 'Software Engineering',
            'bio' => 'Berpengalaman dalam pengembangan aplikasi web dan mobile modern',
            'quota' => 4,
            'used_quota' => 0,
        ]);

        $dosen3 = User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'budi.santoso@university.ac.id',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        \App\Models\Lecturer::create([
            'user_id' => $dosen3->id,
            'nip' => '199005202012121003',
            'expertise' => 'Cybersecurity, Network Security, Cryptography',
            'specialization' => 'Information Security',
            'bio' => 'Spesialis keamanan informasi dan jaringan',
            'quota' => 3,
            'used_quota' => 0,
        ]);

        // Create Mahasiswa users with profiles
        $mahasiswa1 = User::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi.wijaya@student.university.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        \App\Models\Student::create([
            'user_id' => $mahasiswa1->id,
            'nim' => '2021110001',
            'study_program' => 'Teknik Informatika',
            'semester' => 7,
        ]);

        $mahasiswa2 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@student.university.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        \App\Models\Student::create([
            'user_id' => $mahasiswa2->id,
            'nim' => '2021110002',
            'study_program' => 'Teknik Informatika',
            'semester' => 7,
        ]);

        $mahasiswa3 = User::create([
            'name' => 'Reza Pratama',
            'email' => 'reza.pratama@student.university.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        \App\Models\Student::create([
            'user_id' => $mahasiswa3->id,
            'nim' => '2021110003',
            'study_program' => 'Teknik Informatika',
            'semester' => 6,
        ]);
    }
}
