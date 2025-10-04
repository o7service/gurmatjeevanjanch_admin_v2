<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        
        if ($brands->isEmpty()) {
            return response()->json([
                "success" => false,
                "status" => 400,
                "message" => "Brands not found",
            ]);
        } else {
            return response()->json([
                "success" => true,
                "status" => 200,
                "message" => "Brands loaded successfully",
                "data" => $brands
            ]);
        }
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
        $validatedData = $request->validate([
            'name' => 'required',
            'logo' => 'required',
        ]);

        
        $existingBrand = Brand::where('name', $validatedData['name'])->get()->first();
        if (!$existingBrand) {
            $newImageName = null;
            if ($request->hasFile('logo')) {
                $file = $request->file("logo");
                $newImageName = time() . "-" . $file->getClientOriginalName();
                $file->move(public_path('brands'), $newImageName);
            }
            $newBrand = new Brand();
            $newBrand->name = $validatedData['name'];
            $newBrand->logo =  $newImageName;
            $newBrand->save();
            return response()->json([
                "success" => true,
                "status" => 201,
                "message" => "Brand added successfully",
                "data" => $newBrand
            ]);
        } else {
            return response()->json([
                "success" => false,
                "status" => 400,
                "message" => "Brand already exist"
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                "success" => false,
                "status" => 404,
                "message" => "Brand not found"
            ]);
        } else {
            return response()->json([
                "success" => true,
                "status" => 200,
                "message" => "Brand Loaded",
                "data" => $brand
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        if ($request->name) {
            $brand->name = $request->name;
        } else {
            $brand->name = $brand->name;
        }
        $newImageName = null;
        if ($request->hasFile('logo')) {
            $file = $request->file("logo");
            $newImageName = time() . "-" . $file->getClientOriginalName();
            $file->move(public_path('brands'), $newImageName);
        }
        if ($newImageName !== null) {
            $brand->logo = $newImageName;
        } else {
            $brand->logo = $brand->logo;
        }
        $brand->save();
        return response()->json([
            "success" => true,
            "status" => 200,
            "message" => "Brand updated successfully",
            "data" =>  $brand
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return response()->json([
            "success" => true,
            "status" => 200,
            "message" => "Brand Deleted"
        ]);
    }
}
