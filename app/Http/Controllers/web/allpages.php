<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class allpages extends Controller
{
    //

    public function privacypolicy()
    {
        return view('sitepages/privacypolicy');
    }
    public function termsandconditions()
    {
        return view('sitepages/termsandconditions');
    }

}
