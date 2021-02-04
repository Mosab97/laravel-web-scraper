<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function uploadImage($file, $path = '')
    {
        $fileName = $file->getClientOriginalName();
        $file_exe = $file->getClientOriginalExtension();
        $new_name = uniqid() . '.' . $file_exe;
        $directory = 'uploads' . '/' . $path;//.'/'.date("Y").'/'.date("m").'/'.date("d");
        $destienation = public_path($directory);
        $file->move($destienation, $new_name);
        return $directory . '/' . $new_name;
    }

    protected function apiSuccess($data = null, $message = 'success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'status' => $status,
            'message' => is_null($message) ? 'Success' : $message,

        ],
            $status)->header('Content-Type', 'application/json');
    }

    protected function paginate($object)
    {
        return [
            'current_page' => $object->currentPage(),
            //'items' => $object->items(),
            'first_page_url' => $object->url(1),
            'from' => $object->firstItem(),
            'last_page' => $object->lastPage(),
            'last_page_url' => $object->url($object->lastPage()),
            'next_page_url' => $object->nextPageUrl(),
            'per_page' => $object->perPage(),
            'prev_page_url' => $object->previousPageUrl(),
            'to' => $object->lastItem(),
            'total' => $object->total(),
        ];
    }

}
