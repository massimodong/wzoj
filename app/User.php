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
	    if(isset(Session::get('roles', [])['admin'])) return true;
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

    public function topics(){
	    return $this->hasMany('App\ForumTopic');
    }
    public function replies(){
	    return $this->hasMany('App\ForumReply');
    }

    public function manage_groups(){
	    if($this->has_role('admin')) return DB::table('groups');
	    return $this->hasMany('App\Group', 'manager_id');
    }
    public function manage_problems(){
	    if($this->has_role('admin')) return DB::table('problems');
	    return $this->hasMany('App\Problem', 'manager_id');
    }
    public function manage_problemsets(){
	    if($this->has_role('admin')) return DB::table('problemsets');
	    return $this->hasMany('App\Problemset', 'manager_id');
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

    public function max_scores($problemset_id, $problems){
	    $max_scores = [];
	    $uncached_problems = [];
	    foreach($problems as $problem){
		    $path = $this->id.'-'.$problemset_id.'-'.$problem->id;
		    if(Cache::tags(['problemsets', 'max_score'])->has($path)){
			    $max_scores[$problem->id] = Cache::tags(['problemsets', 'max_score'])->get($path);
		    }else{
			    $max_scores[$problem->id] = -1;
			    array_push($uncached_problems, $problem->id);
			    Cache::tags(['problemsets', 'max_score'])->put($path, -1, CACHE_ONE_DAY);
		    }
	    }
	    if(!empty($uncached_problems)){
		    $mc = $this->solutions()
			    ->where('problemset_id', '=', $problemset_id)
			    ->whereIn('problem_id', $uncached_problems)
			    ->groupBy('problem_id')
			    ->select(DB::raw('problem_id, max(score) as score'))
			    ->get();
		    foreach($mc as $problem){
			    $max_scores[$problem->problem_id] = $problem->score;
			    $path = $this->id.'-'.$problemset_id.'-'.$problem->problem_id;
			    Cache::tags(['problemsets', 'max_score'])->put($path, $problem->score, CACHE_ONE_DAY);
		    }
	    }
	    return $max_scores;
    }

    public function problemsets(){
	    if($this->has_role('problemset_manager')) return \App\Problemset::all()->all();
	    $problemsets_last_updated_at = Cache::tags(['wzoj'])->rememberForever('problemsets_last_updated_at', function(){
			return time();
	    });

	    $user_last_updated_at = Session::get('problemsets_last_updated_at');
	    if((isset($user_last_updated_at)) && $user_last_updated_at >= $problemsets_last_updated_at){
		    return Session::get('problemsets');
	    }else{
		    Session::put('problemsets_last_updated_at', time());
		    //problemsets
		    $problemsets_id = [];
		    $problemsets = [];
		    $groups = $this->groups()->with('problemsets')->get();
		    foreach($groups as $group){
			    foreach($group->problemsets as $problemset){
				    if(!isset($problemsets_id[$problemset->id])){
					    $problemsets_id[$problemset->id] = true;
					    array_push($problemsets, $problemset);
				    }
			    }
		    }
		    $public_problemsets = \App\Problemset::where('public', true)->get();
		    foreach($public_problemsets as $problemset){
			    if(!isset($problemsets_id[$problemset->id])){
				    $problemsets_id[$problemset->id] = true;
				    array_push($problemsets, $problemset);
			    }
		    }
		    Session::put('problemsets', $problemsets);
		    return $problemsets;
	    }
    }

    public function __get($key){
	    if($this->has_role('admin')){
		    switch($key){
			    case 'manage_groups':
				    return \App\Group::all();
			    case 'manage_problems':
				    return \App\Problem::all();
			    case 'manage_problemsets':
				    return \App\Problemset::all();
		    }
	    }
	    return $this->getAttribute($key);
    }
}
