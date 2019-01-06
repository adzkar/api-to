<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call(VerificationSeeder::class);
      $this->call(CommitteesSeeder::class);
      $this->call(ParticipantsSeeder::class);
    }
}
