<?php

use Illuminate\Database\Seeder;

class TestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Tests::class)
        ->create()
        ->each(function($test) {
          // $test->questions()
          //      ->saveMany(
          //          factory(App\Questions::class, 5)
          //          ->make()
          //      );
            factory(App\Questions::class,5)
            ->create(['id_test' => $test->id_test])
            ->each(function($question) {

                factory(App\Answers::class,4)
                ->create(['id_question' => $question->id_question]);

                factory(App\Answers::class)
                ->states('t')
                ->create(['id_question' => $question->id_question]);

            });
        });
    }
}
