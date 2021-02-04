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
        if ($response_status_code == 200) {
            $dom = HtmlDomParser::str_get_html($html);

            $get_elements = $dom->find('div[class="col-12 col-md-3 col-xl-3  pl-0 p-1"]');
            foreach ($get_elements as $element) {
                $store = new RealEstate();
                $store->price = trim($element->find('button[class="price2"]', 0)->text());
                $store->title = trim($element->find('h6[class="card-title  mb-0"]', 0)->text());
                $store->save();
                $get_images = $element->find('img[class="card-img-top rounded-0 w-100 lazy"]');
                foreach ($get_images as $index => $image) {
                    $image_store = new RealEstateImages();
                    $image_store->image = $image->attr['src'];
                    $image_store->real_estate_id = $store->id;
                    $image_store->save();
                }
            }
        }

        return view('home');
    }
}
