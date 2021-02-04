<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RealEsateResource;
use App\RealEstate;
use App\RealEstateImages;
use Illuminate\Http\Request;

class RealEstateController extends Controller
{
    private $_model;

    public function __construct(RealEstate $realEstate)
    {
        $this->_model = $realEstate;
    }

    public function real_estates(Request $request)
    {
        $limit = isset($request->limit) ? $request->limit : 10;
        $real_estate = $this->_model->paginate($limit);
//        return $this->apiSuccess(RealEsateResource::collection($real_estate));

        return $this->apiSuccess([
            'items' => RealEsateResource::collection($real_estate->items()),
            'paginate' => $this->paginate($real_estate),
        ]);
    }

}
