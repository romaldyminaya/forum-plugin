<?php namespace RainLab\Forum\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class ChannelsAddHiddenAndModerated extends Migration
{

    public function up()
    {
        Schema::table('rainlab_forum_channels', function($table)
        {
            $table->boolean('is_hidden')->default(0);
            $table->boolean('is_moderated')->default(0);
        });
    }

    public function down()
    {
        if (Schema::hasTable('rainlab_forum_channels'))
        {
            Schema::table('rainlab_forum_channels', function($table)
            {
                $table->dropColumn('is_hidden', 'is_moderated');
            });
        }
    }
}
