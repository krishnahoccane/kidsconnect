<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class urlRestrictionController extends Controller
{
    //
    public function adminProfile()
    {
        return view('users/adminProfile');
    }
    public function adminUsers()
    {
        return view('users/adminUsers');
    }
    public function appUsers()
    {
        return view('users/appUsers');
    }
    public function createdUsers()
    {
        return view('users/createdUsers');
    }
    public function feedback()
    {
        return view('users/feedback');
    }
    public function userProfile()
    {
        return view('users/userProfile');
    }
    public function about()
    {
        return view('pages/about');
    }
    public function termsAndconditions()
    {
        return view('pages/termsAndconditions');
    }
    public function privacyPolicy()
    {
        return view('pages/privacyPolicy');

    }
    public function faq()
    {
        return view('pages/faq');

    }

    public function banner()
    {
        return view('pages/banner');

    }
    public function totalRegistrations()
    {
        return view('reports/totalRegistrations');
    }
    public function totalCalendarschedules()
    {
        return view('reports/totalCalendarschedules');
    }
    public function appFeedbacks()
    {
        return view('reports/appFeedbacks');
    }
}
