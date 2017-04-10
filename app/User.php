<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Cache;
use Session;
use DB;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'fullname', 'fullname_lock', 'class', 'class_lock', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function groups(){
	    return $this->belongsToMany('App\Group');
    }

    public function roles(){
	    return $this->belongsToMany('App\Role');
    }

    public function has_role($role){
	    return isset(Session::get('roles', [])[$role]);
    }

    public function solutions(){
	    return $this->hasMany('App\Solution');
    }

    public function answerfiles(){
	    return $this->hasMany('App\Answerfile');
    }

    public function files(){
	    return $this->hasMany('App\File');
    }

    public function update_cnt_ac(){
	    $this->cnt_ac = $this->solutions()
	    			->distinct('problem_id')
	    			->where('score', '>=', 100)
	    			->count('problem_id');
	    $this->save();
    }

    public function scopeWithoutAdmin($query){
	    return $query->whereNotIn('id', function($query){
			    $query->select('user_id')
			          ->from('role_user')
				  ->where('role_id', 1);//role_id 1 must be admin
		});
    }

    public function isbot($v){
	    User::where('id', $this->id)->increment('bot_tendency', $v);
    }

    public function max_scores($problemset, $problems){
	    $max_scores = [];
	    $uncached_problems = [];
	    foreach($problems as $problem){
		    $path = $this->id.'-'.$problemset->id.'-'.$problem->id;
		    if(Cache::tags(['problemsets', 'max_score'])->has($path)){
			    $max_scores[$problem->id] = Cache::tags(['problemsets', 'max_score'])->get($path);
		    }else{
			    array_push($uncached_problems, $problem->id);
			    Cache::tags(['problemsets', 'max_score'])->put($path, -1, CACHE_ONE_DAY);
		    }
	    }
	    if(!empty($uncached_problems)){
		    $mc = $this->solutions()
			    ->where('problemset_id', '=', $problemset->id)
			    ->whereIn('problem_id', $uncached_problems)
			    ->groupBy('problem_id')
			    ->select(DB::raw('problem_id, max(score) as score'))
			    ->get();
		    foreach($mc as $problem){
			    $max_scores[$problem->problem_id] = $problem->score;
			    $path = $this->id.'-'.$problemset->id.'-'.$problem->problem_id;
			    Cache::tags(['problemsets', 'max_score'])->put($path, $problem->score, CACHE_ONE_DAY);
		    }
	    }
	    return $max_scores;
    }
}
