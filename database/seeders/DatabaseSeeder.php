<?php

namespace Database\Seeders;

use App\Models\User;
use Novaon\Menus\Models\Weekday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // \App\Models\User::factory(10)->create();
    User::create([
      'name' => 'Admin',
      'email' => 'admin@test.com',
      'password' => Hash::make('admin'),
      'role' => 'admin'
    ]);
    User::create([
      'name' => 'Editor',
      'email' => 'editor@test.com',
      'password' => Hash::make('editor'),
      'role' => 'editor'
    ]);
    User::create([
      'name' => 'User',
      'email' => 'user@test.com',
      'password' => Hash::make('user'),
      'role' => 'user'
    ]);
    Weekday::create([
      'weekday' => 'Thứ 2',
    ]);
    Weekday::create([
      'weekday' => 'Thứ 3',
    ]);
    Weekday::create([
      'weekday' => 'Thứ 4',
    ]);
    Weekday::create([
      'weekday' => 'Thứ 5',
    ]);
    Weekday::create([
      'weekday' => 'Thứ 6',
    ]);
    Weekday::create([
      'weekday' => 'Thứ 7',
    ]);
  }
}
