<?php

namespace App\Http\Controllers\api;

use App\Models\Stores;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResources;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);


        if($validator->fails()){
            $storeNameErrorMessage = $validator->errors()->messages()['name'][0];
            return response()->json([
                "status" => "fails",
                "message" => $storeNameErrorMessage
            ], 400);
        }

        $store['name'] = $request->get('name');
        $store['sellerId'] = auth('seller-api')->id();
        $store['logo'] = $request->file('logo')->store(
            'storeLogo', 'public'
        );

        $affected = Stores::create($store);


        if(!$affected){
            return response()->json([
                "status" => "fails",
                "message" => 'Failed Inserting Data!'
            ], 400);
        }

        $storeData = Stores::where('storeId', $affected->storeId)->get();

        if($storeData){
            return response()->json([
                "status" => "success",
                "data" => $storeData,
                "message" => 'Successfully inserting data!'
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stores  $stores
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $sellerId = auth('seller-api')->id();

        $storeData = new StoreResources(Stores::all()->where('sellerId', $sellerId));
        
       
        $result = [
            'status' => 'success',
            'data' => $storeData,
            'message' => 'Successfully Retrieving Data.'
        ];

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stores  $stores
     * @return \Illuminate\Http\Response
     */
    public function edit(Stores $stores)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stores  $stores
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stores $stores)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stores  $stores
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stores $stores)
    {
        //
    }
}
