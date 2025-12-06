<?php

namespace Database\Seeders;

use App\Models\V1\Account;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      if (App::environment('production')) {
        $this->command->warn('⏭  AccountSeeder skipped in PRODUCTION environment.');
        return;
      }

      // ---- Accept dynamic input from CLI ---- //
      $count = (int) $this->command->ask(
        'How many accounts do you want to seed?',
        5
      );

      $this->command->info("🚀 Creating {$count} test accounts...");

      $faker = Faker::create();

      // Ensure a test user exists
      $userIds = \App\Models\User::role('user')->pluck('id')->toArray();

      DB::transaction(function () use ($faker, $count, $userIds) {
        for ($i = 1; $i <= $count; $i++) {
          $faker = Faker::create();
          Account::create([
            'user_id'       => $userIds[array_rand($userIds)],
            'account_name'  => 'Test Account ' . $i,
            'client_id'     => strtoupper($faker->bothify('CID####')),
            'api_key'       => strtolower($faker->bothify('SEC####')),
            'client_secret' => strtolower($faker->bothify('SEC####')),
            'totp_secret'   => strtolower($faker->bothify('SEC####')),

            'session_token' => $faker->sha1(),
            'refresh_token' => $faker->sha1(),
            'token_expiry'  => now()->addDays(rand(1, 30)),

            'is_active'     => $faker->boolean(),
            'status'        => $faker->randomElement(['idle', 'logged_in', 'error']),
            'last_error'    => null,
            'last_login_at' => now()->subDays(rand(1, 10)),
          ]);
        }
      });

      $this->command->info('✅ Account test data created successfully!');
    }
}
