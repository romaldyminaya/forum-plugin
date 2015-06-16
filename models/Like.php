<?php namespace RainLab\Forum\Models;

use Model;

/**
 * Like Model
 */
class Like extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rainlab_forum_likes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['post_id', 'member_id'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'post' => ['RainLab\User\Models\Post'],
        'member' => ['RainLab\User\Models\Member'],
    ];

    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}