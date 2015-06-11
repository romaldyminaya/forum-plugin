<?php namespace RainLab\Forum\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddAnswerSupport extends Migration
{
    public function up()
    {
        Schema::table('rainlab_forum_topics', function($table)
        {
            $table->boolean('is_answered')->default(false)->after('is_locked');
        });

        Schema::table('rainlab_forum_posts', function($table)
        {
            $table->boolean('is_answer')->default(false)->after('topic_id');
        });
    }

    public function down()
    {
        Schema::table('rainlab_forum_topics', function($table)
        {
            $table->dropColumn('is_answered');
        });

        Schema::table('rainlab_forum_posts', function($table)
        {
            $table->dropColumn('is_answer');
        });
    }
}
