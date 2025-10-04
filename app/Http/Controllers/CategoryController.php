<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('category.index', compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $newImageName = null;

        if ($request->hasFile('image')) {
            $file = $request->file("image");
            // print_r($file);
            // echo $file->getClientOriginalName();
            // echo $file->getClientOriginalExtension();
            $newImageName = time() . "-" . $file->getClientOriginalName();
            // echo $newImageName;
            $file->move(public_path('category_images'), $newImageName);
        }
        // print_r($request);
        // echo $request['name'];
        // die();
        // echo $request['description'];

        // Category::create([
        //     "name" => $request['name'],
        //     "image" => $request['image'],
        //     "description" => $request['description'],
        // ]);

        $newCategory = new Category();
        $newCategory->name = $request->name;
        $newCategory->image = $newImageName;
        $newCategory->description = $request->description;
        $newCategory->save();

        return redirect(route('category.index'))->with("success", "Category Added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        // echo $category['name'];
        // die();
        return view('category.edit', compact('category'));
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
        $category = Category::findOrFail($id);
        $newImageName = null;
        if ($request->hasFile('new_image')) {
            $file = $request->file("new_image");
            $newImageName = time() . "-" . $file->getClientOriginalName();
            $file->move(public_path('category_images'), $newImageName);
        } else {
            $newImageName = $request->old_image;
        }
        $category->name =  $request->name;
        $category->image =  $newImageName;
        $category->description =  $request->description;
        $category->save();
        return redirect(route('category.index'))->with("success", "Category Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        echo $id;
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with("success", "Category Deleted ");
    }
}
