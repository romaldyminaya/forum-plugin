<?php namespace RainLab\Forum\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateProfilesTable extends Migration
{

    public function up()
    {
        Schema::create('rainlab_forum_profiles', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->integer('points_required')->unsigned()->default(100);

            $table->timestamps();
        });

        Schema::table('rainlab_forum_members', function($table)
        {
            $table->integer('profile_id')->nullable();
            $table->integer('count_points')->default(0);
            $table->dateTime('points_updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rainlab_forum_profiles');

        if(Schema::hasTable('rainlab_forum_members'))
        {
            Schema::table('rainlab_forum_members', function($table)
            {
                $table->dropColumn('profile_id');
                $table->dropColumn('count_points');
                $table->dropColumn('points_updated_at');
            });
        }
    }
}