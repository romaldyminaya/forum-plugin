<?php namespace RainLab\Forum\Models;

use Model;

/**
 * Profile Model
 */
class Profile extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rainlab_forum_profiles';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array The attributes that should be visible in arrays.
     */
    protected $visible = ['title', 'points_required'];

    public $rules = [
        'title'                  => 'required',
        'points_required'        => 'required',
    ];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'icon' => ['System\Models\File']
    ];
    public $attachMany = [];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords($value);
    }

    public function scopeByPoints($query, $points = 0)
    {
        return $query->where('points_required', '<=', $points)
            ->orderBy('points_required', 'desc')
            ->first();
    }

}