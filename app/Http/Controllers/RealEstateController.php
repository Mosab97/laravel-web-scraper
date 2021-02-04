<?php

namespace App\Http\Controllers;

use App\RealEstate;
use App\RealEstateImages;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:100',
            'price' => 'required|numeric',
            'images' => 'nullable|array',
        ]);
        if (isset($request->real_estate_id)) {
            $store = $this->_model->findOrFail($request->real_estate_id);
        } else {
            $store = new $this->_model();
        }
        $store->title = $request->title;
        $store->price = $request->price;
        if (isset($request->images) && is_array($request->images)) {
            foreach ($request->images as $index => $image) {
                $image_store = new RealEstateImages();
                $image_store->image = $this->uploadImage($image, 'real_estate');
                $image_store->real_estate_id = $store->id;
                $image_store->save();
            }
        }
        if (isset($request->real_estate_id)) {
            return redirect()->route('real_estate.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('real_estate.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }

    }
}
