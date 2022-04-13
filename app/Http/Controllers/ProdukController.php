<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Produk;
class ProdukController extends Controller
{

    public function store(Request $request) {


        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:100',
            'product_type' => 'required|in:makanan,minuman',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);
        
        if($validator->fails()) {
            
            return response()->json($validator->messages())->setStatusCode(422);
        }
        
        $payload = $validator->validate();
        Produk::create([
            'product_name' => $payload['product_name'],
            'product_type' => $payload['product_type'],
            'product_price' => $payload['product_price'],
            'expired_at' => $payload['expired_at']
        ]);
        
        return response()->json([
            'msg' => 'Data produk berhasil disimpan'
        ],201);
    }
    
    function showAll(){
        
        $produks = Produk::all();
        
        return response()->json([
            'msg' => 'Data Produk keseluruhan',
            'data' => $produks
        ],200);
    
    }
    
    function showById($id){

        $produks = Produk::where('id',$id)->first();

        if($produks) {

            return response()->json([
                'msg' => 'Data produk dengan ID: '.$id,
                'Data' => $produks
            ],200);

        }

        return response()->json([
            'msg' => 'Data produk dengan ID: '.$id.' tidak ditemukan'
        ],404);
    }

    public function showByName($product_name){

        $produks = Produk::where('product_name','LIKE','%'.$product_name.'%')->get();

        if($produks->count() > 0) {

            return response()->json([
                'msg' => 'Data produk dengan nama yang mirip: '.$product_name,
                'data' => $produks
            ],200);
        }

        return reponse()->json([
            'msg' => 'Data produk dengan nama yang mirip: '.$product_name.'tidak ditemukan',
        ],404);
    }

    public function update(Request $request,$id){

        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:100',
            'product_type' => 'required|in:makanan,minuman',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);

        if($validator->fails()) {

            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        produk::where('id',$id)->update([
            'product_name' => $payload['product_name'],
            'product_type' => $payload['product_type'],
            'product_price' => $payload['product_price'],
            'expired_at' => $payload['expired_at']
        ]);

        return response()->json([
            'msg' => 'Data produk berhasil diubah'
        ],201);
    }

    public function delete($id) {

        $produks = produk::where('id',$id)->get();

        if($produks){

            produk::where('id',$id)->delete();

            return response()->json([
                'msg' => 'Data produk dengan ID: '.$id.'Berhasil dihapus'
            ],200);

        }

        return response()-json([
            'msg' => 'Data produk dengan ID: '.$id.'Tidak ditemukan'
        ],404);
    }

}
