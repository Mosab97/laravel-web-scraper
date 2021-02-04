<?php

namespace App\Http\Controllers;

use App\RealEstate;
use App\RealEstateImages;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {


        return view('home');
    }
}
