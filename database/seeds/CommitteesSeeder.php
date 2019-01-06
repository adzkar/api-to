<?php

use Illuminate\Database\Seeder;

class CommitteesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Committees::class, 5)
        ->create();
    }
}
