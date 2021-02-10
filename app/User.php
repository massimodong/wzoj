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

use Auth;

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
    protected $fillable = ['name', 'email', 'fullname', 'fullname_lock', 'class', 'class_lock', 'password', 'last_token'];

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
	    return $this->belongsToMany('App\Role')->withPivot('remark');
    }

    public function has_role($role){
	    if(isset(Session::get('roles', [])['admin'])) return true;
	    return isset(Session::get('roles', [])[$role]);
    }

    public function virtual_participations(){
      return $this->hasMany('App\VirtualParticipation');
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
	    if($this->has_role('admin')) return \App\Group::query();
	    return $this->hasMany('App\Group', 'manager_id');
    }
    public function manage_problems(){
	    if($this->has_role('admin')) return \App\Problem::query();
	    return $this->hasMany('App\Problem', 'manager_id');
    }
    public function manage_problemsets(){
	    if($this->has_role('admin')) return \App\Problemset::query();
	    return $this->hasMany('App\Problemset', 'manager_id');
    }

    public function update_cnt_ac(){
	    $this->cnt_ac = $this->solutions()
	    			->distinct('problem_id')
	    			->where('score', 100)
	    			->count('problem_id');
	    $this->save();
    }

    public function scopeWithoutAdmin($query){
	    return $query->whereNotIn('id', function($query){
			    $query->select('user_id')
			          ->from('role_user')
				  ->whereIn('role_id', [1, 2]);
		});
    }

    public function isbot($v){
	    User::where('id', $this->id)->increment('bot_tendency', $v);
    }

    public function problemsets(){
	    if($this->has_role('admin')) return \App\Problemset::all();
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
		    $problemsets = collect();
		    $groups = $this->groups()->with('problemsets')->get();
		    foreach($groups as $group){
			    foreach($group->problemsets as $problemset){
				    if(!isset($problemsets_id[$problemset->id])){
					    $problemsets_id[$problemset->id] = true;
              $problemsets->push($problemset);
				    }
			    }
		    }
		    $public_problemsets = \App\Problemset::where('public', true)->get();
		    foreach($public_problemsets as $problemset){
			    if(!isset($problemsets_id[$problemset->id])){
				    $problemsets_id[$problemset->id] = true;
            $problemsets->push($problemset);
			    }
		    }
		    if($this->has_role('problemset_manager')){
			    foreach(\App\Problemset::where('manager_id', $this->id)->get() as $problemset){
				    if(!isset($problemsets_id[$problemset->id])){
					    $problemsets_id[$problemset->id] = true;
              $problemsets->push($problemset);
				    }
			    }
		    }
		    Session::put('problemsets', $problemsets);
		    return $problemsets;
	    }
    }

    public function get_description(){
      if(Auth::check() && (Auth::user()->id == $this->id || Auth::user()->has_role('admin'))){
        return $this->new_description;
      }else{
        if(strtotime('-6 hours') > strtotime($this->description_changed_at)){
          if($this->stored_description != $this->new_description){
            $this->stored_description = $this->new_description;
            $this->save();
          }
        }
        return $this->stored_description;
      }
    }

    public function avatar_url($size){
      if($this->avatar_token) return '/files/avatar/'.$this->id.'/avatar-'.$size.'.png?t='.$this->avatar_token;
      else return '/files/avatar/default/avatar-'.$size.'.png';
    }

    public function shortname($length){
      if($this->fullname) return mb_substr($this->fullname, 0, $length);
      else return mb_substr($this->name, 0, $length);
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

      switch($key){
        case 'description':
          return $this->get_description();
      }

      return $this->getAttribute($key);
    }
}
