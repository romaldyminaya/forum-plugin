<?php namespace RainLab\Forum;

use Event;
use Backend;
use RainLab\User\Models\User;
use RainLab\Forum\Models\Member;
use System\Classes\PluginBase;
use RainLab\User\Controllers\Users as UsersController;

/**
 * Forum Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rainlab.forum::lang.plugin.name',
            'description' => 'rainlab.forum::lang.plugin.description',
            'author'      => 'Alexey Bobkov, Samuel Georges',
            'icon'        => 'icon-comments',
            'homepage'    => 'https://github.com/rainlab/forum-plugin'
        ];
    }

    public function boot()
    {
        /**
         * Add relations
         */
        User::extend(function($model) {
            $model->hasOne['forum_member'] = ['RainLab\Forum\Models\Member'];
        });

        UsersController::extendFormFields(function($widget, $model, $context) {
            if ($context != 'update') return;
            if (!Member::getFromUser($model)) return;

            $widget->addFields([
                'forum_member[username]' => [
                    'label'   => 'rainlab.forum::lang.settings.username',
                    'tab'     => 'Forum',
                    'comment' => 'rainlab.forum::lang.settings.username_comment'
                ],
                'forum_member[is_moderator]' => [
                    'label'   => 'rainlab.forum::lang.settings.moderator',
                    'type'    => 'checkbox',
                    'tab'     => 'Forum',
                    'span'    => 'auto',
                    'comment' => 'rainlab.forum::lang.settings.moderator_comment'
                ],
                'forum_member[is_banned]' => [
                    'label'   => 'rainlab.forum::lang.settings.banned',
                    'type'    => 'checkbox',
                    'tab'     => 'Forum',
                    'span'    => 'auto',
                    'comment' => 'rainlab.forum::lang.settings.banned_comment'
                ]
            ], 'primary');
        });

        UsersController::extendListColumns(function($widget, $model) {
            if (!$model instanceof \RainLab\User\Models\User) return;

            $widget->addColumns([
                'forum_member_username' => [
                    'label'      => 'rainlab.forum::lang.settings.forum_username',
                    'relation'   => 'forum_member',
                    'select'     => 'username',
                    'searchable' => true
                ],
                // 'forum_member_profile' => [
                //     'label'      => 'rainlab.forum::lang.settings.forum_profile',
                //     'relation'   => 'profile',
                //     'select'     => 'title',
                //     'searchable' => true
                // ],
                'forum_member_reputation' => [
                    'label'      => 'rainlab.forum::lang.settings.forum_reputation',
                    'relation'   => 'forum_member',
                    'select'     => 'reputation',
                    'searchable' => false
                ],
                'forum_member_points' => [
                    'label'      => 'rainlab.forum::lang.settings.forum_points',
                    'relation'   => 'forum_member',
                    'select'     => 'points',
                    'searchable' => false
                ],
            ]);
        });
    }

    public function registerComponents()
    {
        return [
           '\RainLab\Forum\Components\Channels'     => 'forumChannels',
           '\RainLab\Forum\Components\Channel'      => 'forumChannel',
           '\RainLab\Forum\Components\Topic'        => 'forumTopic',
           '\RainLab\Forum\Components\Topics'       => 'forumTopics',
           '\RainLab\Forum\Components\Member'       => 'forumMember',
           '\RainLab\Forum\Components\EmbedTopic'   => 'forumEmbedTopic',
           '\RainLab\Forum\Components\EmbedChannel' => 'forumEmbedChannel'
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'rainlab.forum::lang.settings.channels',
                'description' => 'rainlab.forum::lang.settings.channels_desc',
                'icon'        => 'icon-comments',
                'url'         => Backend::url('rainlab/forum/channels'),
                'category'    => 'rainlab.forum::lang.plugin.tab',
                'order'       => 500,
                'permissions' => ['rainlab.forum.access_settings'],
                'keywords'    => 'rainlab.forum::lang.settings.keywords',
            ],
            'profiles_settings' => [
                'label'       => 'rainlab.forum::lang.profiles_settings.profiles',
                'description' => 'rainlab.forum::lang.profiles_settings.profiles_desc',
                'icon'        => 'icon-list-ol',
                'url'         => Backend::url('rainlab/forum/profiles'),
                'category'    => 'rainlab.forum::lang.plugin.tab',
                'order'       => 500,
                'permissions' => ['rainlab.forum.access_profiles_settings'],
                'keywords'    => 'rainlab.forum::lang.settings.keywords',
            ],

            'rating_settings' => [
                'label'       => 'rainlab.forum::lang.rating_settings.menu_label',
                'description' => 'rainlab.forum::lang.rating_settings.menu_description',
                'category'    => 'rainlab.forum::lang.plugin.tab',
                'icon'        => 'icon-line-chart',
                'class'       => 'RainLab\Forum\Models\Settings',
                'order'       => 500,
                'permissions' => ['rainlab.forum.access_rating_settings'],
                'keywords'    => 'rainlab.forum::lang.settings.keywords'
            ]
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'rainlab.forum::mail.topic_reply' => 'Notification to followers when a post is made to a topic.',
            'rainlab.forum::mail.member_report' => 'Notification to moderators when a member is reported to be a spammer.'
        ];
    }

    public function registerPermissions()
    {
        return [
            'rainlab.forum.access_settings' => ['tab' => 'rainlab.forum::lang.plugin.tab', 'label' => 'rainlab.forum::lang.plugin.access_settings'],
            'rainlab.forum.access_profiles_settings' => ['tab' => 'rainlab.forum::lang.plugin.tab', 'label' => 'rainlab.forum::lang.plugin.access_profiles_settings'],
            'rainlab.forum.access_rating_settings' => ['tab' => 'rainlab.forum::lang.plugin.tab', 'label' => 'rainlab.forum::lang.plugin.access_rating_settings'],
        ];
    }
}
