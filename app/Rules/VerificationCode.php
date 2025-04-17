<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Auth;

class VerificationCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user, $action, $req)
    {
        $this->user = $user;
        $this->action = $action;
        $this->req = $req;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      $verification = $this->user->verification_codes()
        ->where('task', $this->action)
        ->where('verified', false)
        ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-10 minute')))
        ->orderBy('created_at', 'desc')
        ->first();

      if(is_null($verification)) return false;

      foreach($verification->params as $key => $val){
        if($this->req[$key] !== $val) return false;
      }
      if($verification->code !== $value) return false;

      $verification->verified = true;
      $verification->save();
      return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('wzoj.invalid_verification_code');
    }
}
