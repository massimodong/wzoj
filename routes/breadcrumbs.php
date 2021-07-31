<?php

// Home
Breadcrumbs::for('home', function($breadcrumbs)
{
    $breadcrumbs->push(trans('wzoj.home'), '/');
});

// Home > Problemsets
Breadcrumbs::for('problemsets', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('wzoj.problemsets'), '/s');
});

// Home > contests
Breadcrumbs::for('contests', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('wzoj.contests'), '/contests');
});

// Home > Problemsets / Contests > Problemset
Breadcrumbs::for('problemset', function($breadcrumbs, $problemset)
{
    if($problemset->type === 'set') $breadcrumbs->parent('problemsets');
    else $breadcrumbs->parent('contests');
    $breadcrumbs->push($problemset->name, '/s/'.$problemset->id);
});

// Home > Problemsets / Contests > Problemset > Problem
Breadcrumbs::for('problem', function($breadcrumbs, $problemset, $problem)
{
    $breadcrumbs->parent('problemset', $problemset);
    $breadcrumbs->push($problem->name, '/s/'.$problemset->id.'/'.$problem->id);
});

// Home > Problemsets / Contests > Problemset > Ranklist
Breadcrumbs::for('contest_ranklist', function($breadcrumbs, $problemset)
{
    $breadcrumbs->parent('problemset', $problemset);
    $breadcrumbs->push(trans('wzoj.ranklist'), '/s/'.$problemset->id.'/ranklist');
});

// Home > Solutions
Breadcrumbs::for('solutions', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('wzoj.solutions'), '/solutions');
});

// Home > Solutions > Solution
Breadcrumbs::for('solution', function($breadcrumbs, $solution)
{
    $breadcrumbs->parent('solutions');
    $breadcrumbs->push('#'.$solution->id, '/solutions/'.$solution->id);
});

// Home > Ranklist
Breadcrumbs::for('ranklist', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('wzoj.user_rank_list'), '/ranklist');
});

// Home > DiyPage
Breadcrumbs::for('diy_page', function($breadcrumbs, $diyPage)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push($diyPage->name, '/'.$diyPage->url);
});

// Home > User
Breadcrumbs::for('user', function($breadcrumbs, $user)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push($user->name.' ('.trans('wzoj.user').')', '/'.$user->id);
});
