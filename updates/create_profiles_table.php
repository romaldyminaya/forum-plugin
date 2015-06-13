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

        Schema::table('users', function($table)
        {
            $table->integer('profile_id')->nullable();
            $table->integer('points')->default(0); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('rainlab_forum_profiles');

        Schema::table('users', function($table)
        {
            $table->dropColumn('profile_id');
            $table->dropColumn('points');
        });
    }
}