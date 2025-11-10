<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

     
        $users = [
            ['first_name' => 'Ali', 'last_name' => 'Reza', 'phone_number' => '09123456789'],
            ['first_name' => 'Sara', 'last_name' => 'Ahmad', 'phone_number' => '09123456780'],
            ['first_name' => 'Mohammad', 'last_name' => 'Karimi', 'phone_number' => '09123456781'],
            ['first_name' => 'Mina', 'last_name' => 'Mohammadi', 'phone_number' => '09123456782'],
            ['first_name' => 'Reza', 'last_name' => 'Peyman', 'phone_number' => '09123456783'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'phone_number' => $user['phone_number'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
