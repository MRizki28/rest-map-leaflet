<?php

namespace App\Http\Controllers;

use App\Models\RukoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RukoController extends Controller
{
    public function getAllData()
    {
        $data = RukoModel::all();
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function createData(Request $request)
    {
        $validation  = Validator::make($request->all(), [
            'nama_ruko' => 'required',
            'gambar_ruko' => 'required|image',
            'latitude' => 'required',
            'longtitude' => 'required'
        ]);

        if ($validation->fails()) {
            return response([
                'errors' => $validation->errors()
            ]);
        }

        try {
            $data = new RukoModel;
            $data->nama_ruko = $request->input('nama_ruko');
            if ($request->hasFile('gambar_ruko')) {
                $file = $request->file('gambar_ruko');
                $extention = $file->getClientOriginalExtension();
                $filename = 'RUKO-' . Str::random(10) . '.' . $extention;
                Storage::makeDirectory('uploads/ruko');
                $file->move(public_path('uploads/ruko'), $filename);
                $data->gambar_ruko = $filename;
            }
            $data->latitude = $request->input('latitude');
            $data->longtitude = $request->input('longtitude');
            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data'=> $data
        ]);
    }
}
