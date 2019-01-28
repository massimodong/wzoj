<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push(trans('wzoj.home'), '/');
});

// Home > Problemsets
Breadcrumbs::register('problemsets', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('wzoj.problemsets'), '/s');
});

// Home > contests
Breadcrumbs::register('contests', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('wzoj.contests'), '/contests');
});
