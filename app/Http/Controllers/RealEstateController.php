<?php

namespace App\Http\Controllers;

use App\RealEstate;
use App\RealEstateImages;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;

class RealEstateController extends Controller
{
    private $_model;

    public function __construct(RealEstate $realEstate)
    {
        $this->_model = $realEstate;
        $this->middleware('auth');
    }

    public function index()
    {
        $real_esats = $this->_model->paginate(5);
        return view('real_estate.index', compact('real_esats'));
    }

    public function create()
    {
        return view('real_estate.create');
    }

    public function edit(Request $request, $id)
    {
        $real_estate = $this->_model->findOrFail($id);
        return view('real_estate.create', compact('real_estate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:100',
            'price' => 'required',
            'images' => 'nullable|array',
        ]);
        if (isset($request->real_estate_id)) {
            $store = $this->_model->findOrFail($request->real_estate_id);
        } else {
            $store = new $this->_model();
        }
        $store->title = $request->title;
        $store->price = $request->price;
        $store->save();
        if (isset($request->images) && is_array($request->images)) {
            $store->images()->delete();
            foreach ($request->images as $index => $image) {
                $image_store = new RealEstateImages();
                $image_store->image = asset($this->uploadImage($image, 'real_estate'));
                $image_store->real_estate_id = $store->id;
                $image_store->save();
            }
        }
        if (isset($request->real_estate_id)) {
            return redirect()->route('real_estate.index')->with('m-class', 'success')->with('message', 'Successfully Updated');
        } else {
            return redirect()->route('real_estate.index')->with('m-class', 'success')->with('message', 'Successfully Created');
        }

    }

    public function destroy(Request $request, $id)
    {
        $item = $this->_model->findOrFail($id);
        $item->delete();
        return redirect()->route('real_estate.index')->with('m-class', 'success')->with('message', 'Deleted Successfully');

    }

    public function get_data(Request $request)
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

        return redirect()->route('real_estate.index')->with('m-class', 'success')->with('message', 'Data saved Successfully');

    }
}
