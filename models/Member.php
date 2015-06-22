<?php namespace RainLab\Forum\Models;

use Db;
use Str;
use Auth;
use Model;
use Carbon\Carbon;
use Html;
use Markdown;

/**
 * Member Model
 */
class Member extends Model
{
    use \October\Rain\Database\Traits\Sluggable;

    /**
     * @var Integer Minutes in within the member is considered online
     */
    protected $onlineOffset = 15;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rainlab_forum_members';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['username'];

    /**
     * @var array The attributes that should be visible in arrays.
     */
    protected $visible = [
        'username', 
        'slug', 
        'reputation'
    ];

    /**
     * @var array Auto generated slug
     */
    public $slugs = ['slug' => 'username'];

    public $dates = ['last_active_at', 'points_updated_at'];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User'],
        'profile' => ['RainLab\Forum\Models\Profile']
    ];
    
    /**
     * @var array Relations
     */
    public $hasMany = [
        'posts' => ['RainLab\Forum\Models\Post', 'order' => 'created_at desc']
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];

    /**
     * @var array Relations
     */
    public $hasManyThrough = [
        'likes' => [
            'RainLab\Forum\Models\Like',
            'through' => 'RainLab\Forum\Models\Post'
        ],
    ];
    
    /**
     * Filter just the online members
     */
    public function scopeOnline($query)
    {
        return $query->where('last_active_at', '>=', Carbon::now()->subMinutes($this->onlineOffset))
            ->orderBy('last_active_at', 'desc');
    }

    /**
     * After an existing model has been populated.
     */
    public function afterFetch()
    {
        $this->updatePoints();
    }

    /**
     * Automatically creates a forum member for a user if not one already.
     * @param  RainLab\User\Models\User $user
     * @return RainLab\Forum\Models\Member
     */
    public static function getFromUser($user = null)
    {
        if ($user === null)
            $user = Auth::getUser();

        if (!$user)
            return null;

        if (!$user->forum_member) {
            $generatedUsername = explode('@', $user->email);
            $generatedUsername = array_shift($generatedUsername);
            $generatedUsername = Str::limit($generatedUsername, 24, '') . $user->id;

            $member = new static;
            $member->user = $user;
            $member->username = $generatedUsername;

            $member->save();

            $user->forum_member = $member;
        }

        return $user->forum_member;
    }

    /**
     * Can the specified member edit this member
     * @param  self $member
     * @return bool
     */
    public function canEdit($member = null)
    {
        if (!$member)
            $member = Member::getFromUser();

        if (!$member)
            return false;

        if ($this->id == $member->id)
            return true;

        if ($member->is_moderator)
            return true;

        return false;
    }

    public function beforeCreate()
    {
        $this->profile_id = Profile::byPoints(0)->id;
    }

    public function beforeSave()
    {
        /*
         * Reset the slug
         */
        if ($this->isDirty('username')) {
            $this->slug = null;
            $this->slugAttributes();
        }

        $this->bio_html = Html::clean(Markdown::parse(trim($this->bio)));
    }

    /**
     * Returns true if this member is following this topic.
     * @param  Topic  $topic
     * @return boolean
     */
    public function isFollowing($topic)
    {
        return TopicFollow::check($topic, $this);
    }

    public function touchActivity()
    {
        return $this
            ->newQuery()
            ->where('id', $this->id)
            ->update(['last_active_at' => Carbon::now()]);
    }

    /**
     * Sets the "url" attribute with a URL to this object
     * @param string $pageName
     * @param Cms\Classes\Controller $controller
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    public function banMember()
    {
        $this->is_banned = ($this->is_banned == 1 ? 0 : 1);
        $this->save();
    }

    /**
     * Updates the member reputation based on likes and unlikes
     */
    public function updateReputation()
    {
        $totals = Db::table('rainlab_forum_posts as p')
                        ->select(Db::raw('(sum(p.count_likes) - sum(p.count_unlikes)) as reputation'))
                        ->whereMemberId($this->id)
                        ->first();
        $this->reputation = $totals->reputation;
        $this->save();
    }

    /**
     * Update the member Points
     */
    public function updatePoints()
    {
        /**
         * Check if the member poitns has been updated in the past minutes
         */
        if($this->points_updated_at->diffInMinutes() < $this->onlineOffset)
        {
            return;
        }

        $ratings = Settings::instance();

        $count_topics = Db::table('rainlab_forum_topics')
                        ->whereStartMemberId($this->id)
                        ->count();
        $count_posts = Db::table('rainlab_forum_posts')
                        ->whereNotNull('subject')
                        ->whereMemberId($this->id)
                        ->count();
        $count_answers = Db::table('rainlab_forum_posts')
                        ->whereisAnswer(true)
                        ->whereMemberId($this->id)
                        ->count();

        //Calculate the points
        $points = ( $count_topics  * $ratings->topic );
        $points += ( $count_posts * $ratings->post );
        $points += ( $count_answers * $ratings->answer );

        //Asign the points and the profile
        $this->count_points      = $points;
        $this->profile_id        = Profile::byPoints($points)->id;
        $this->count_topics      = $count_topics;
        $this->count_posts       = $count_posts;
        $this->count_answers     = $count_answers;
        $this->points_updated_at = Carbon::now();
        $this->save();
    }

    /**
     * Check weather the current user is online or not
     * @var bool
     */
    public function isOnline()
    {
        $diff = $this->last_active_at->diffInMinutes(); 
        
        return ($diff <= $this->onlineOffset);
    }
}