<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();

        foreach ($students as $student) {

            if (!$student->nama_ayah) continue;

            // Generate email ortu
            $emailBase = strtolower(str_replace(' ', '.', $student->nama_ayah));
            $email = $emailBase . '@sekolah.sch.id';

            if (User::where('email', $email)->exists()) {
                $email = $this->makeUniqueEmail($emailBase . '@sekolah.sch.id');
            }

            // Username ortu
            $username = 'ortu.' . $student->nis;

            // Password secure random
            $password = Str::password(10);

            // Buat akun user orang tua
            $parentUser = User::create([
                'username' => $username,
                'name'     => $student->nama_ayah,
                'email'    => $email,
                'role'     => 'parent',
                'password' => Hash::make($password),
            ]);

            // Bisa disimpan di student jika ada kolom parent_user_id
            if ($student->fillable) {
                $student->update([
                    'parent_user_id' => $parentUser->id
                ]);
            }
        }
    }

    private function makeUniqueEmail($email)
    {
        if (!User::where('email', $email)->exists()) return $email;

        $base = explode('@', $email)[0];
        $domain = explode('@', $email)[1];

        $counter = 2;
        $newEmail = $base . $counter . '@' . $domain;

        while (User::where('email', $newEmail)->exists()) {
            $counter++;
            $newEmail = $base . $counter . '@' . $domain;
        }

        return $newEmail;
    }
}
