<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        //Get url param for scraping
        $url = $request->get('url');
        $url = 'https://summerhometurkey.com/property-istanbul';

        //Init Guzzle
        $client = new Client();

        //Get request
        $response = $client->request(
            'GET',
            $url
        );

        $response_status_code = $response->getStatusCode();
        $html = $response->getBody()->getContents();
        if($response_status_code==200){
            $dom = HtmlDomParser::str_get_html( $html );

//            $song_items = $dom->find('div[class="chart-list-item"]');
            $song_items = $dom->find('h6[class="card-title"]');
            dd($song_items[0]->text());
            $count = 1;
            foreach ($song_items as $song_item){
                if($count==1){
                    $song_title = trim($song_item->find('span[class="chart-list-item__title-text"]',0)->text());
                    $song_artist = trim($song_item->find('div[class="chart-list-item__artist"]',0)->text());

                    $song_lyrics_parent = $song_item->find('div[class="chart-list-item__lyrics"]',0)->find('a',0);
                    $song_lyrics_href = $song_lyrics_parent->attr['href'];

                    //Store in database
                }
                $count++;
            }
        }

        return view('home');
    }
}
