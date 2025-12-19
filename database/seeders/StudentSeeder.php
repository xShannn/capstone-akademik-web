<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Mulai seeding students...');

        // Ambil semua kelas
        $classrooms = DB::table('classrooms')->orderBy('tingkat')->orderBy('nama_kelas')->get();

        // Daftar nama siswa Indonesia
        $maleNames = [
            'Ahmad',
            'Budi',
            'Cahyo',
            'Dodi',
            'Eko',
            'Fajar',
            'Guntur',
            'Hadi',
            'Iwan',
            'Joko',
            'Kurniawan',
            'Lukman',
            'Maman',
            'Nugroho',
            'Oki',
            'Prasetyo',
            'Rizki',
            'Surya',
            'Teguh',
            'Umar',
            'Wawan',
            'Yudi',
            'Zainal',
            'Abdul',
            'Bayu',
            'Candra',
            'Darmawan',
            'Eri',
            'Fadli',
            'Galih',
            'Hendra',
            'Indra',
            'Jaya',
            'Kusuma',
            'Lutfi',
            'Muhammad',
            'Nanda',
            'Omar',
            'Purnomo',
            'Rendi'
        ];

        $femaleNames = [
            'Ani',
            'Bunga',
            'Citra',
            'Dewi',
            'Eka',
            'Fitri',
            'Gita',
            'Hani',
            'Intan',
            'Juli',
            'Kartika',
            'Lestari',
            'Maya',
            'Nurul',
            'Oktavia',
            'Putri',
            'Rani',
            'Sari',
            'Tika',
            'Umi',
            'Wulan',
            'Yuni',
            'Zahra',
            'Aisyah',
            'Bella',
            'Cinta',
            'Diah',
            'Elisa',
            'Fani',
            'Gadis',
            'Hana',
            'Indah',
            'Jihan',
            'Kamilah',
            'Lia',
            'Mila',
            'Nadia',
            'Olivia',
            'Puspita',
            'Qori'
        ];

        $lastNames = [
            'Saputra',
            'Santoso',
            'Wibowo',
            'Pratama',
            'Setiawan',
            'Kurniawan',
            'Hidayat',
            'Susanto',
            'Wijaya',
            'Yulianto',
            'Siregar',
            'Halim',
            'Gunawan',
            'Rahman',
            'Nasution',
            'Purnomo',
            'Mulyadi',
            'Siregar',
            'Sihombing',
            'Sinaga',
            'Simanjuntak',
            'Sitompul',
            'Situmorang',
            'Nainggolan',
            'Saragih',
            'Purba',
            'Simbolon',
            'Lumban Gaol',
            'Lumban Tobing',
            'Manalu',
            'Siahaan',
            'Sihite',
            'Silitonga',
            'Hutapea',
            'Situmorang',
            'Pardede',
            'Sihombing',
            'Tampubolon',
            'Hutagalung',
            'Marpaung'
        ];

        // Daftar nama ayah
        $fatherNames = [
            'Abdul',
            'Bambang',
            'Cahya',
            'Darmawan',
            'Eko',
            'Firmansyah',
            'Gunawan',
            'Hari',
            'Irawan',
            'Jaya',
            'Kusnadi',
            'Lukman',
            'Mulyono',
            'Nugroho',
            'Oman',
            'Purnomo',
            'Rahmat',
            'Suryadi',
            'Teguh',
            'Usman',
            'Wahyudi',
            'Yusuf',
            'Zainal',
            'Arif',
            'Budi',
            'Candra',
            'Dwi',
            'Eri',
            'Fajar',
            'Ginanjar',
            'Hendro',
            'Irfan',
            'Joko',
            'Kurnia',
            'Lutfi',
            'Maman',
            'Nanda',
            'Oki',
            'Pramono',
            'Rudi'
        ];

        // Daftar nama ibu
        $motherNames = [
            'Ani',
            'Budiarti',
            'Citra',
            'Dewi',
            'Eka',
            'Fitriani',
            'Gita',
            'Hartini',
            'Indah',
            'Juli',
            'Kartini',
            'Lestari',
            'Murni',
            'Nurhayati',
            'Oktaviani',
            'Puspita',
            'Rahayu',
            'Sari',
            'Tri',
            'Utami',
            'Wati',
            'Yuliani',
            'Zahra',
            'Aminah',
            'Bella',
            'Cinta',
            'Dian',
            'Elisa',
            'Fani',
            'Gadis',
            'Hani',
            'Intan',
            'Jihan',
            'Kusuma',
            'Lia',
            'Mila',
            'Nina',
            'Olivia',
            'Putri',
            'Ratna'
        ];

        // Pekerjaan
        $occupations = [
            'Pegawai Negeri',
            'Guru',
            'Dosen',
            'Dokter',
            'Perawat',
            'Bidan',
            'Pengusaha',
            'Wiraswasta',
            'Pedagang',
            'Karyawan Swasta',
            'Buruh',
            'Petani',
            'Nelayan',
            'Pensiunan',
            'Ibu Rumah Tangga',
            'Driver Online',
            'Freelancer',
            'Teknisi',
            'Montir',
            'Tukang Kayu',
            'Tukang Bangunan'
        ];

        // Agama
        $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

        // Kecamatan di Lampung (untuk distribusi alamat)
        $lampungDistricts = [
            ['kota' => 'Bandar Lampung', 'kecamatan' => ['Tanjungkarang Timur', 'Tanjungkarang Pusat', 'Telukbetung Barat', 'Telukbetung Timur', 'Kedaton', 'Sukarame']],
            ['kota' => 'Metro', 'kecamatan' => ['Metro Pusat', 'Metro Timur', 'Metro Barat']],
            ['kota' => 'Lampung Selatan', 'kecamatan' => ['Kalianda', 'Natar', 'Jati Agung', 'Tanjungsari']],
            ['kota' => 'Lampung Tengah', 'kecamatan' => ['Gunung Sugih', 'Terbanggi Besar', 'Seputih Raman']],
            ['kota' => 'Lampung Timur', 'kecamatan' => ['Sukadana', 'Labuhan Ratu', 'Way Jepara']],
            ['kota' => 'Lampung Barat', 'kecamatan' => ['Liwa', 'Balik Bukit', 'Sumber Jaya']],
            ['kota' => 'Tanggamus', 'kecamatan' => ['Kota Agung', 'Talang Padang', 'Wonosobo']],
            ['kota' => 'Pringsewu', 'kecamatan' => ['Pringsewu', 'Adiluwih', 'Pardasuka']],
            ['kota' => 'Way Kanan', 'kecamatan' => ['Blambangan Umpu', 'Baradatu', 'Bahuga']],
            ['kota' => 'Lampung Utara', 'kecamatan' => ['Kotabumi', 'Abung Selatan', 'Abung Timur']],
            ['kota' => 'Tulang Bawang', 'kecamatan' => ['Menggala', 'Tulang Bawang Tengah', 'Tulang Bawang Udik']],
            ['kota' => 'Pesisir Barat', 'kecamatan' => ['Krui', 'Pesisir Tengah', 'Pesisir Selatan']],
        ];

        $totalStudents = 0;
        $totalUsers = 0;
        $totalParents = 0;

        foreach ($classrooms as $classroom) {
            $tingkat = (int)$classroom->tingkat;

            for ($i = 1; $i <= 20; $i++) {
                $totalStudents++; // Increment global counter

                for ($i = 1; $i <= 20; $i++) {
                    // Generate data unik
                    $tahunAngkatan = date('Y') - $tingkat + 1;
                    $tahunShort = substr($tahunAngkatan, 2, 2);
                    $nis = $tahunShort . str_pad($totalStudents, 4, '0', STR_PAD_LEFT);

                    $nisn = $tahunAngkatan . str_pad($totalStudents, 8, '0', STR_PAD_LEFT);

                    // Tentukan jenis kelamin (50:50)
                    $gender = ($i % 2 == 0) ? 'Perempuan' : 'Laki-laki';

                    // Pilih nama sesuai jenis kelamin
                    if ($gender === 'Laki-laki') {
                        $firstName = $maleNames[array_rand($maleNames)];
                    } else {
                        $firstName = $femaleNames[array_rand($femaleNames)];
                    }

                    $lastName = $lastNames[array_rand($lastNames)];
                    $fullName = $firstName . ' ' . $lastName;

                    // Generate email
                    $email = strtolower(str_replace(' ', '.', $firstName . '.' . $lastName)) . rand(1, 99) . '@student.sch.id';

                    // Tanggal lahir (usia 6-12 tahun untuk SD)
                    $currentYear = date('Y');
                    $birthYear = $currentYear - (6 + $tingkat);
                    $birthMonth = rand(1, 12);
                    $birthDay = rand(1, 28);
                    $birthDate = Carbon::create($birthYear, $birthMonth, $birthDay)->format('Y-m-d');

                    // Pilih lokasi acak di Lampung
                    $location = $lampungDistricts[array_rand($lampungDistricts)];
                    $city = $location['kota'];
                    $district = $location['kecamatan'][array_rand($location['kecamatan'])];

                    // Generate NIK
                    $nik = '18' . date('dmy', strtotime($birthDate)) . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

                    // Data orang tua
                    $fatherName = $fatherNames[array_rand($fatherNames)] . ' ' . $lastName;
                    $motherName = $motherNames[array_rand($motherNames)] . ' ' . $lastName;
                    $fatherJob = $occupations[array_rand($occupations)];
                    $motherJob = (rand(0, 1)) ? $occupations[array_rand($occupations)] : 'Ibu Rumah Tangga';

                    // === 1. BUAT AKUN STUDENT ===
                    $studentUsername = $nis; // Username = NIS
                    $studentPassword = Hash::make('password123'); // Password default

                    $studentUserId = DB::table('users')->insertGetId([
                        'username' => $studentUsername,
                        'name' => $fullName,
                        'email' => $email,
                        'password' => $studentPassword,
                        'role' => 'student',
                        'email_verified_at' => Carbon::now(),
                        'remember_token' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    $totalUsers++;

                    // === 2. BUAT AKUN PARENT ===
                    $parentUsername = 'ortu_' . $nis; // Username: ortu_NIS
                    $parentPassword = Hash::make(date('dmY', strtotime($birthDate))); // Password: tanggal lahir anak

                    $parentUserId = DB::table('users')->insertGetId([
                        'username' => $parentUsername,
                        'name' => $fatherName, // Nama ayah sebagai nama user
                        'email' => null,
                        'password' => $parentPassword,
                        'role' => 'parent',
                        'email_verified_at' => null,
                        'remember_token' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    $totalUsers++;
                    $totalParents++;

                    // === 3. BUAT DATA STUDENT ===
                    DB::table('students')->insert([
                        'user_id' => $studentUserId,
                        'role' => 'student',
                        'parent_user_id' => $parentUserId,
                        'classroom_id' => $classroom->id,

                        // Identitas murid
                        'nisn' => $nisn,
                        'nis' => $nis,
                        'nama_lengkap' => $fullName,
                        'jenis_kelamin' => $gender,
                        'tempat_lahir' => $city,
                        'tanggal_lahir' => $birthDate,
                        'agama' => $religions[array_rand($religions)],
                        'nik' => $nik,

                        // Kontak & Alamat
                        'nomor_telepon' => '08' . rand(12, 15) . rand(1000000, 9999999),
                        'email' => $email,
                        'alamat' => 'Jl. ' . $firstName . ' No. ' . rand(1, 100),
                        'provinsi' => 'Lampung',
                        'kabupaten' => $city,
                        'kecamatan' => $district,
                        'kelurahan' => 'Kelurahan ' . $firstName,
                        'dusun' => 'Dusun ' . rand(1, 10),
                        'kode_pos' => '35' . rand(100, 999),

                        // Akademik
                        'tahun_masuk' => $currentYear - $tingkat + 1, // Tahun masuk berdasarkan kelas
                        'status_aktif' => 'Aktif',

                        // Orang tua/wali
                        'nama_ayah' => $fatherName,
                        'pekerjaan_ayah' => $fatherJob,
                        'nama_ibu' => $motherName,
                        'pekerjaan_ibu' => $motherJob,
                        'nomor_telepon_ortu' => '08' . rand(16, 19) . rand(1000000, 9999999),

                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $totalStudents++;
                }

                $this->command->info("Kelas {$classroom->nama_kelas}: 20 siswa berhasil dibuat");
            }

            $this->command->info('================== SEEDER SELESAI ==================');
            $this->command->info("Total siswa: {$totalStudents} (20 siswa × 18 kelas)");
            $this->command->info("Total akun user dibuat: {$totalUsers} ({$totalStudents} student + {$totalParents} parent)");
            $this->command->info("Distribusi per kelas: 20 siswa");
            $this->command->info("Distribusi per tingkat:");

            // Summary per tingkat
            $summary = [];
            for ($tingkat = 1; $tingkat <= 6; $tingkat++) {
                $count = DB::table('students')
                    ->join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                    ->where('classrooms.tingkat', $tingkat)
                    ->count();
                $summary[] = "Kelas {$tingkat}: {$count} siswa";
            }

            $this->command->info(implode(', ', $summary));
            $this->command->info('================== INFORMASI AKUN ==================');
            $this->command->info('Akun Student:');
            $this->command->info('- Username: NIS (contoh: 240101)');
            $this->command->info('- Password: password123');
            $this->command->info('');
            $this->command->info('Akun Parent:');
            $this->command->info('- Username: ortu_NIS (contoh: ortu_240101)');
            $this->command->info('- Password: tanggal lahir anak (format: DDMMYYYY)');
            $this->command->info('');
            $this->command->info('Contoh login student:');
            $this->command->info('- Email: ahmad.saputra23@student.sch.id');
            $this->command->info('- Username: 240101');
            $this->command->info('- Password: password123');
        }
    }
}
