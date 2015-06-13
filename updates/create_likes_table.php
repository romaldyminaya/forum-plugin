<?php namespace RainLab\Forum\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateLikesTable extends Migration
{

    public function up()
    {
        Schema::create('rainlab_forum_likes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('post_id')->nullable();
            $table->integer('member_id')->nullable();
            $table->boolean('like')->default(false);
            $table->boolean('unlike')->default(false);
            
            $table->index(['post_id', 'member_id']);
            
            $table->timestamps();
        });

        Schema::table('users', function($table)
        {
            $table->integer('reputation')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rainlab_forum_likes');

        Schema::table('users', function($table)
        {
            $table->dropColumn('reputation');
        });
    }

}
