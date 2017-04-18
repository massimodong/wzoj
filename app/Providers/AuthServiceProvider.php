<?php

namespace App\Providers;

use App\User;
use App\Problemset;
use App\Solution;
use App\ForumTopic;
use App\ForumReply;

use App\Policies\UserPolicy;
use App\Policies\ProblemsetPolicy;
use App\Policies\SolutionPolicy;
use App\Policies\ForumTopicPolicy;
use App\Policies\ForumReplyPolicy;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
	User::class => UserPolicy::class,
	Problemset::class => ProblemsetPolicy::class,
	Solution::class => SolutionPolicy::class,
	ForumTopic::class => ForumTopicPolicy::class,
	ForumReply::class => ForumReplyPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
