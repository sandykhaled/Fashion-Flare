<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\ResponseTrait;

class CategoryController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function store(CategoryRequest $request)
    {
        try {
            $request->merge([
                'slug' => Str::slug($request->post('name'))
            ]);
            $data = $request->except('image');
            $data['image'] = $this->uploadFile($request);
            Category::create($data);
            return $this->responseSuccess([], 'Category added successfully');
        }
        catch (\Exception $exception){
            return $this->responseError([],$exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        try {
            // Check if the category exists
            $category = Category::find($id);

            if (!$category) {
                return $this->responseError([], 'Category not found.');
            }

            $old_image = $category->image;

            $data = $request->except('image');

            // Update the 'slug' if the 'name' is being updated
            if ($request->has('name') && $request->input('name') !== $category->name) {
                $data['slug'] = Str::slug($request->input('name'));
            }

            // Upload the new image and update the 'image' field in $data
            $new_image = $this->uploadFile($request);

            if ($new_image) {
                $data['image'] = $new_image;
            }

            $category->update($data);

            if ($new_image && $old_image) {
                $this->deleteFile($old_image);
            }

            return $this->responseSuccess([], 'Category updated successfully');
        } catch (\Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }


}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category=Category::findOrFail($id);
            $category->delete();
            if($category->image){
                Storage::disk('public')->delete($category->image);
            }
            return $this->responseSuccess([], 'Category deleted successfully');
        } catch (\Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }

    }
    protected function uploadFile(Request $request){
        if(! $request->hasFile('image')){
            return;
        }

        $file = $request->file('image');
        $path=$file->store('uploads',['disk'=>'public']);
        return $path;

    }
}
