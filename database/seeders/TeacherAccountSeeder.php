<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TeacherAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $this->command->info('Mulai seeding teachers dengan user...');

            // Data guru dengan alamat di Lampung
            $teachers = [
                // ==================== WALI KELAS ====================
                [
                    'user_data' => [
                        'username' => 'ahmad.hidayat',
                        'name' => 'Dr. Ahmad Hidayat, M.Pd.',
                        'email' => 'ahmad.hidayat@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '197805152000121001',
                        'nama_lengkap' => 'Dr. Ahmad Hidayat, M.Pd.',
                        'jenis_kelamin' => 'Laki-laki',
                        'tempat_lahir' => 'Bandar Lampung',
                        'tanggal_lahir' => '1978-05-15',
                        'agama' => 'Islam',
                        'nik' => '1871051505780001',
                        'nomor_telepon' => '081234567890',
                        'email' => 'ahmad.hidayat@sekolah.sch.id',
                        'alamat' => 'Jl. Wolter Monginsidi No. 12',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Kota Bandar Lampung',
                        'kecamatan' => 'Tanjungkarang Timur',
                        'kelurahan' => 'Sukarame',
                        'dusun' => 'Sukarame I',
                        'kode_pos' => '35121',
                        'jabatan' => 'Wali Kelas',
                        'status' => 'Aktif',
                        'tanggal_masuk' => '2015-08-01',
                    ]
                ],
                [
                    'user_data' => [
                        'username' => 'siti.nurhaliza',
                        'name' => 'Siti Nurhaliza, S.Pd.',
                        'email' => 'siti.nurhaliza@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '198206202006042002',
                        'nama_lengkap' => 'Siti Nurhaliza, S.Pd.',
                        'jenis_kelamin' => 'Perempuan',
                        'tempat_lahir' => 'Metro',
                        'tanggal_lahir' => '1982-06-20',
                        'agama' => 'Islam',
                        'nik' => '1872062006820002',
                        'nomor_telepon' => '081298765432',
                        'email' => 'siti.nurhaliza@sekolah.sch.id',
                        'alamat' => 'Jl. Jenderal Sudirman No. 8',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Kota Metro',
                        'kecamatan' => 'Metro Timur',
                        'kelurahan' => 'Iringmulyo',
                        'dusun' => 'Iringmulyo Timur',
                        'kode_pos' => '34111',
                        'jabatan' => 'Wali Kelas',
                        'status' => 'Aktif',
                        'tanggal_masuk' => '2010-07-15',
                    ]
                ],
                [
                    'user_data' => [
                        'username' => 'budi.santoso',
                        'name' => 'Budi Santoso, S.Pd.',
                        'email' => 'budi.santoso@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '199012102015032003',
                        'nama_lengkap' => 'Budi Santoso, S.Pd.',
                        'jenis_kelamin' => 'Laki-laki',
                        'tempat_lahir' => 'Lampung Selatan',
                        'tanggal_lahir' => '1990-12-10',
                        'agama' => 'Kristen',
                        'nik' => '1873121012900003',
                        'nomor_telepon' => '082112345678',
                        'email' => 'budi.santoso@sekolah.sch.id',
                        'alamat' => 'Jl. Lintas Barat No. 45',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Lampung Selatan',
                        'kecamatan' => 'Kalianda',
                        'kelurahan' => 'Kalianda',
                        'dusun' => 'Kalianda Utara',
                        'kode_pos' => '35551',
                        'jabatan' => 'Wali Kelas',
                        'status' => 'Aktif',
                        'tanggal_masuk' => '2018-01-10',
                    ]
                ],
                [
                    'user_data' => [
                        'username' => 'maria.ulfah',
                        'name' => 'Maria Ulfah, M.Pd.',
                        'email' => 'maria.ulfah@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '198508032010042004',
                        'nama_lengkap' => 'Maria Ulfah, M.Pd.',
                        'jenis_kelamin' => 'Perempuan',
                        'tempat_lahir' => 'Lampung Tengah',
                        'tanggal_lahir' => '1985-08-03',
                        'agama' => 'Katolik',
                        'nik' => '1874080308850004',
                        'nomor_telepon' => '081345678901',
                        'email' => 'maria.ulfah@sekolah.sch.id',
                        'alamat' => 'Jl. Protokol No. 23',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Lampung Tengah',
                        'kecamatan' => 'Gunung Sugih',
                        'kelurahan' => 'Gunung Sugih',
                        'dusun' => 'Gunung Sugih Raya',
                        'kode_pos' => '34161',
                        'jabatan' => 'Wali Kelas',
                        'status' => 'Cuti',
                        'tanggal_masuk' => '2012-03-15',
                    ]
                ],

                // ==================== GURU OLAHRAGA ====================
                [
                    'user_data' => [
                        'username' => 'rizki.pratama',
                        'name' => 'Rizki Pratama, S.Pd.',
                        'email' => 'rizki.pratama@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '198904152012051005',
                        'nama_lengkap' => 'Rizki Pratama, S.Pd.',
                        'jenis_kelamin' => 'Laki-laki',
                        'tempat_lahir' => 'Bandar Lampung',
                        'tanggal_lahir' => '1989-04-15',
                        'agama' => 'Islam',
                        'nik' => '1871051504890005',
                        'nomor_telepon' => '081512345678',
                        'email' => 'rizki.pratama@sekolah.sch.id',
                        'alamat' => 'Jl. Teuku Umar No. 7',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Kota Bandar Lampung',
                        'kecamatan' => 'Tanjungkarang Pusat',
                        'kelurahan' => 'Palapa',
                        'dusun' => 'Palapa Raya',
                        'kode_pos' => '35112',
                        'jabatan' => 'Guru Olahraga',
                        'status' => 'Aktif',
                        'tanggal_masuk' => '2014-06-01',
                    ]
                ],
                [
                    'user_data' => [
                        'username' => 'dewi.fitriani',
                        'name' => 'Dewi Fitriani, S.Pd.',
                        'email' => 'dewi.fitriani@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '199203202018032006',
                        'nama_lengkap' => 'Dewi Fitriani, S.Pd.',
                        'jenis_kelamin' => 'Perempuan',
                        'tempat_lahir' => 'Lampung Timur',
                        'tanggal_lahir' => '1992-03-20',
                        'agama' => 'Islam',
                        'nik' => '1875032003920006',
                        'nomor_telepon' => '082345678901',
                        'email' => 'dewi.fitriani@sekolah.sch.id',
                        'alamat' => 'Jl. Raya Sukadana No. 15',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Lampung Timur',
                        'kecamatan' => 'Sukadana',
                        'kelurahan' => 'Sukadana',
                        'dusun' => 'Sukadana Ilir',
                        'kode_pos' => '34194',
                        'jabatan' => 'Guru Olahraga',
                        'status' => 'Aktif',
                        'tanggal_masuk' => '2019-07-20',
                    ]
                ],

                // ==================== GURU MENGAJI ====================
                [
                    'user_data' => [
                        'username' => 'abdullah.syafei',
                        'name' => 'KH. Abdullah Syafei',
                        'email' => 'abdullah.syafei@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '197512102005011007',
                        'nama_lengkap' => 'KH. Abdullah Syafei',
                        'jenis_kelamin' => 'Laki-laki',
                        'tempat_lahir' => 'Lampung Barat',
                        'tanggal_lahir' => '1975-12-10',
                        'agama' => 'Islam',
                        'nik' => '1876121012750007',
                        'nomor_telepon' => '081678901234',
                        'email' => 'abdullah.syafei@sekolah.sch.id',
                        'alamat' => 'Jl. Lintas Liwa No. 1',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Lampung Barat',
                        'kecamatan' => 'Balik Bukit',
                        'kelurahan' => 'Pasar Liwa',
                        'dusun' => 'Liwa Raya',
                        'kode_pos' => '34815',
                        'jabatan' => 'Guru Mengaji',
                        'status' => 'Aktif',
                        'tanggal_masuk' => '2008-01-05',
                    ]
                ],
                [
                    'user_data' => [
                        'username' => 'ummi.kalsum',
                        'name' => 'Ummi Kalsum, S.Ag.',
                        'email' => 'ummi.kalsum@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '198011252010042008',
                        'nama_lengkap' => 'Ummi Kalsum, S.Ag.',
                        'jenis_kelamin' => 'Perempuan',
                        'tempat_lahir' => 'Tanggamus',
                        'tanggal_lahir' => '1980-11-25',
                        'agama' => 'Islam',
                        'nik' => '1877112511800008',
                        'nomor_telepon' => '081789012345',
                        'email' => 'ummi.kalsum@sekolah.sch.id',
                        'alamat' => 'Jl. Raya Kota Agung No. 5',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Tanggamus',
                        'kecamatan' => 'Kota Agung',
                        'kelurahan' => 'Kota Agung',
                        'dusun' => 'Kota Agung Pusat',
                        'kode_pos' => '35384',
                        'jabatan' => 'Guru Mengaji',
                        'status' => 'Aktif',
                        'tanggal_masuk' => '2013-08-15',
                    ]
                ],
                [
                    'user_data' => [
                        'username' => 'muhammad.fauzan',
                        'name' => 'Muhammad Fauzan, S.Pd.I.',
                        'email' => 'muhammad.fauzan@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '199305152020032009',
                        'nama_lengkap' => 'Muhammad Fauzan, S.Pd.I.',
                        'jenis_kelamin' => 'Laki-laki',
                        'tempat_lahir' => 'Pringsewu',
                        'tanggal_lahir' => '1993-05-15',
                        'agama' => 'Islam',
                        'nik' => '1878051505930009',
                        'nomor_telepon' => '082456789012',
                        'email' => 'muhammad.fauzan@sekolah.sch.id',
                        'alamat' => 'Jl. Raya Pringsewu No. 9',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Pringsewu',
                        'kecamatan' => 'Pringsewu',
                        'kelurahan' => 'Fajar Agung',
                        'dusun' => 'Fajar Agung Barat',
                        'kode_pos' => '35373',
                        'jabatan' => 'Guru Mengaji',
                        'status' => 'Aktif',
                        'tanggal_masuk' => '2021-03-01',
                    ]
                ],

                // ==================== GURU DENGAN STATUS LAIN ====================
                [
                    'user_data' => [
                        'username' => 'sri.mulyani',
                        'name' => 'Prof. Dr. Sri Mulyani, M.Pd.',
                        'email' => 'sri.mulyani@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '196810101995021010',
                        'nama_lengkap' => 'Prof. Dr. Sri Mulyani, M.Pd.',
                        'jenis_kelamin' => 'Perempuan',
                        'tempat_lahir' => 'Way Kanan',
                        'tanggal_lahir' => '1968-10-10',
                        'agama' => 'Islam',
                        'nik' => '1879101010680010',
                        'nomor_telepon' => '081890123456',
                        'email' => 'sri.mulyani@sekolah.sch.id',
                        'alamat' => 'Jl. Raya Blambangan Umpu No. 30',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Way Kanan',
                        'kecamatan' => 'Blambangan Umpu',
                        'kelurahan' => 'Blambangan Umpu',
                        'dusun' => 'Blambangan Umpu Kota',
                        'kode_pos' => '34764',
                        'jabatan' => 'Wali Kelas',
                        'status' => 'Pensiun',
                        'tanggal_masuk' => '1998-06-01',
                    ]
                ],
                [
                    'user_data' => [
                        'username' => 'andi.wijaya',
                        'name' => 'Andi Wijaya, S.Pd.',
                        'email' => 'andi.wijaya@sekolah.sch.id',
                        'password' => Hash::make('password123'),
                        'role' => 'teacher',
                        'email_verified_at' => Carbon::now(),
                    ],
                    'teacher_data' => [
                        'nip' => '199108152019031011',
                        'nama_lengkap' => 'Andi Wijaya, S.Pd.',
                        'jenis_kelamin' => 'Laki-laki',
                        'tempat_lahir' => 'Lampung Utara',
                        'tanggal_lahir' => '1991-08-15',
                        'agama' => 'Kristen',
                        'nik' => '1880151508910011',
                        'nomor_telepon' => '082567890123',
                        'email' => 'andi.wijaya@sekolah.sch.id',
                        'alamat' => 'Jl. Raya Kotabumi No. 18',
                        'provinsi' => 'Lampung',
                        'kabupaten' => 'Lampung Utara',
                        'kecamatan' => 'Kotabumi',
                        'kelurahan' => 'Kotabumi',
                        'dusun' => 'Kotabumi Selatan',
                        'kode_pos' => '34511',
                        'jabatan' => 'Guru Olahraga',
                        'status' => 'Pindah',
                        'tanggal_masuk' => '2020-01-15',
                    ]
                ],
            ];

            $userCount = 0;
            $teacherCount = 0;

            foreach ($teachers as $data) {
                try {
                    $this->command->info('Memproses: ' . $data['teacher_data']['nama_lengkap']);

                    // Cek apakah user sudah ada
                    $existingUser = DB::table('users')
                        ->where('email', $data['user_data']['email'])
                        ->orWhere('username', $data['user_data']['username'])
                        ->first();

                    if ($existingUser) {
                        $this->command->warn('User sudah ada: ' . $data['user_data']['email']);
                        $userId = $existingUser->id;
                    } else {
                        // Insert user dengan username
                        $userId = DB::table('users')->insertGetId([
                            'username' => $data['user_data']['username'],
                            'name' => $data['user_data']['name'],
                            'email' => $data['user_data']['email'],
                            'password' => $data['user_data']['password'],
                            'role' => $data['user_data']['role'],
                            'email_verified_at' => $data['user_data']['email_verified_at'],
                            'remember_token' => null,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        $userCount++;
                        $this->command->info('User berhasil dibuat: ' . $data['user_data']['email']);
                    }

                    // Cek apakah teacher sudah ada
                    $existingTeacher = DB::table('teachers')
                        ->where('email', $data['teacher_data']['email'])
                        ->orWhere('nip', $data['teacher_data']['nip'])
                        ->first();

                    if ($existingTeacher) {
                        $this->command->warn('Teacher sudah ada: ' . $data['teacher_data']['nama_lengkap']);
                    } else {
                        // Insert teacher
                        DB::table('teachers')->insert([
                            'user_id' => $userId,
                            'nip' => $data['teacher_data']['nip'],
                            'nama_lengkap' => $data['teacher_data']['nama_lengkap'],
                            'jenis_kelamin' => $data['teacher_data']['jenis_kelamin'],
                            'tempat_lahir' => $data['teacher_data']['tempat_lahir'],
                            'tanggal_lahir' => $data['teacher_data']['tanggal_lahir'],
                            'agama' => $data['teacher_data']['agama'],
                            'nik' => $data['teacher_data']['nik'],
                            'nomor_telepon' => $data['teacher_data']['nomor_telepon'],
                            'email' => $data['teacher_data']['email'],
                            'alamat' => $data['teacher_data']['alamat'],
                            'provinsi' => $data['teacher_data']['provinsi'],
                            'kabupaten' => $data['teacher_data']['kabupaten'],
                            'kecamatan' => $data['teacher_data']['kecamatan'],
                            'kelurahan' => $data['teacher_data']['kelurahan'],
                            'dusun' => $data['teacher_data']['dusun'],
                            'kode_pos' => $data['teacher_data']['kode_pos'],
                            'jabatan' => $data['teacher_data']['jabatan'],
                            'status' => $data['teacher_data']['status'],
                            'tanggal_masuk' => $data['teacher_data']['tanggal_masuk'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        $teacherCount++;
                        $this->command->info('Teacher berhasil dibuat: ' . $data['teacher_data']['nama_lengkap']);
                    }
                } catch (\Exception $e) {
                    $this->command->error('Error pada data ' . $data['teacher_data']['nama_lengkap'] . ': ' . $e->getMessage());
                }
            }

            $this->command->info('Seeding selesai!');
            $this->command->info('User dibuat: ' . $userCount);
            $this->command->info('Teacher dibuat: ' . $teacherCount);
            $this->command->info('================== DISTRIBUSI JABATAN ==================');
            $this->command->info('Wali Kelas: 5 guru (4 Aktif, 1 Cuti, 1 Pensiun)');
            $this->command->info('Guru Olahraga: 3 guru (2 Aktif, 1 Pindah)');
            $this->command->info('Guru Mengaji: 3 guru (3 Aktif)');
        } catch (\Exception $e) {
            $this->command->error('Error seeder: ' . $e->getMessage());
        }
    }
}
