<?php namespace RainLab\Forum\Updates;

use Schema;
use RainLab\Forum\Models\Post;
use RainLab\Forum\Models\Topic;
use RainLab\Forum\Models\Member;
use RainLab\Forum\Models\Channel;
use October\Rain\Database\Updates\Seeder;

class SeedAllTables extends Seeder
{

    public function run()
    {
        $orange = Channel::create([
            'title' => 'Channel Orange',
            'description' => 'A root level forum channel',
        ]);

        $autumn = $orange->children()->create([
            'title' => 'Autumn Leaves',
            'description' => 'Disccusion about the season of falling leaves.'
        ]);

        $autumn->children()->create([
            'title' => 'September',
            'description' => 'The start of the fall season.'
        ]);

        $october = $autumn->children()->create([
            'title' => 'October',
            'description' => 'The middle of the fall season.'
        ]);

        $autumn->children()->create([
            'title' => 'November',
            'description' => 'The end of the fall season.'
        ]);

        $orange->children()->create([
            'title' => 'Summer Breeze',
            'description' => 'Disccusion about the wind at the ocean.'
        ]);

        $green = Channel::create([
            'title' => 'Channel Green',
            'description' => 'A root level forum channel',
        ]);

        $green->children()->create([
            'title' => 'Winter Snow',
            'description' => 'Disccusion about the frosty snow flakes.'
        ]);

        $green->children()->create([
            'title' => 'Spring Trees',
            'description' => 'Disccusion about the blooming gardens.'
        ]);
    }

}
