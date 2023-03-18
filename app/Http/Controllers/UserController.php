<?php

namespace App\Http\Controllers;
use App\models\Blog;
use Illuminate\Http\Request;
use Illuminate\Validation;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

use Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function GetCategories()
    {
        $categories = DB::table('categories')->get();
        return $categories;
    }
    public function storeService(Request $request)
        {
            $count = DB::table('services')->where("user_id",Auth::user()->id)
            ->count() ;
            if($count > 2)
            {
                return response()->json(['error' => 'You have exceeded the maximum number of services.'], 422);
            }
            

        // validate form data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required',
            'category_id' => 'required|integer',
            'city' => 'required|string',
            'country_id' => 'required'
        ]);

        // insert city in cities table
        $city = DB::table('cities')->where('name', '=', $validatedData['city'])
        ->where('country_id', '=', $validatedData['country_id'])
        ->first();
        if(!$city)
        {
            $newcityid = DB::table('cities')->insertGetId([
                "name" => $validatedData['city'],
                "country_id" => $validatedData['country_id']
            ]);
            $cityId = $newcityid;
        }
        else{
            // retrieve city id
        $cityId = $city->id;
        }
         

        // insert service in services table
        $service = DB::table("services")->insert([
            "title" => $validatedData['title'],
            "description" => $validatedData['description'],
            "category_id" => $validatedData['category_id'],
            "city_id" => $cityId,
            "country_id" =>$validatedData['country_id'],
            "price" => $validatedData['price'],
            "user_id" => Auth::user()->id
        ]);

        return response()->json(['success'  => "Service added successfully"],200);
        }

        //get services for a user
        public function GetUserServices()
        {
            
            $services = DB::table("services")->where("user_id", "=", Auth::user()->id)
            ->join("cities","services.city_id","=","cities.id")
            ->join("categories","services.category_id","=","categories.id")
            ->select("services.*","categories.name as catname","cities.name as cname")
            ->get();
            return $services;
        }
        public function deleteservice($id)
        {
            $del = DB::table('services')->where('id', $id)->delete();
            if($del)
            return response()->json(['success' => "Service deleted"],200);
            else 
            return response()->json(['error' => "Error when deleting service"],403);
        }
        public function EditService(Request $request,$id)
        {
            DB::table('services')->where('id', $id)->update([
                "title" => $request->title,
                "price" => $request->price,
                "description" => $request->description
            ]);
        }
        public function uploadImage(Request $request)
        {
            $file = $request->file('image');

            // Generate a unique name for the file
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            // Move the file to the storage directory
            $file->storeAs('public/profile_images', $fileName);

            // Get the URL for the uploaded file
            $imageUrl = Storage::url('profile_images/' . $fileName);

            // Update the user's profile image URL in the database
            $user = Auth::user();
            $user->profile_image_url = $imageUrl;
            $user->save();

            // Return the new profile image URL
            return response()->json([
                'imageUrl' => $imageUrl
            ]);
        }
        public function getprofile()
        {
            $user = Auth::user();
            
            return response()->json([
                'imageUrl' => $user->profile_image_url,
                'email' => $user->email,
                'username' => $user->name
            ]);
        }
        public function updateUsername(Request $request)
        {
            $user = Auth::user();
            $user->name = $request->username;
            $user->save();
            return response()->json([
                'username' => $user->name,
                
            ]);
        }
        public function changePassword(Request $request)
        {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed|different:old_password'
        ]);

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password changed successfully.']);
        }
        public function addBlogPost(Request $request)
        {
            // Validate the user's input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'zipFile' => 'required|file|mimetypes:application/zip|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            // Create a new Blog model instance
            $blog = new Blog;

            // Fill the properties of the Blog model with the user's input
            $blog->title = $request->input('title');
            $blog->description = $request->input('description');
            $blog->user_id = Auth::id();

            // Save the uploaded zip file to the server
            $zipFile = $request->file('zipFile');
            $zipFileName = uniqid() . '.' .$zipFile->getClientOriginalName();
            $zipFile->storeAs('blogs', $zipFileName);

            // Extract the contents of the zip file and save the necessary files
            $zip = new \ZipArchive;
            if ($zip->open(storage_path('app/blogs/'.$zipFileName)) === TRUE) {
                // Create a new directory for the extracted files
                $directory = 'public/blogs/'. $zipFileName;
                Storage::makeDirectory($directory);

                // Extract the files to the new directory
                $zip->extractTo(storage_path('app/'.$directory));
                $zip->close();
                // Find the path to the index.html file
                $indexHtmlPath = '';
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(storage_path('app/'.$directory)));
                foreach ($iterator as $file) {
                    if ($file->getFilename() === 'index.html') {
                        $indexHtmlPath = $file->getPath().'/index.html';
                        break;
                    }
                }
                $indexHtmlPath = str_replace(storage_path('app/'),'', $indexHtmlPath);
                // Save the URL of the directory and the path to the index.html file to the blog model
                $blog->zip_file = Storage::url($directory);
                $blog->index_html_path = Storage::url($indexHtmlPath);
               
            } else {
                // Handle the case where the zip file could not be opened
            }

            // Save the Blog model to the database
            $c=$blog->save();
            if(!$c) {
                return response()->json(['message'=>'title already exist.'], 403);
            }
            // Redirect the user to a confirmation page or back to the main blog page
             return response()->json(['message'=>'Your blog has been submitted for review.'], 200);

        }
        public function getBlogs() {
            $blog = DB::table('blogs')
            ->where('user_id', '=',Auth::id())
            ->get();
            return $blog;
        }
        public function getTheBlog($blogId){
            $blog = DB::table('blogs')
            ->where('id', '=',$blogId)
            ->where('user_id', '=',Auth::id())
            ->first();
            return response()->json(["blog" => $blog]);   
        }
        public function UpdateTheBlog(Request $request)
        {
           
            // Get the blog with the given ID
             $blog = Blog::where('id', $request->input('id'))
             ->where('user_id', Auth::id())
             ->first();

        // Check if the user is updating the zip file
        $updateZipFile = false;
        if ($request->hasFile('zipFile')) {
            $updateZipFile = true;
            $validator = Validator::make($request->all(), [
                'zipFile' => 'required|file|mimetypes:application/zip|max:2048',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
        }

        // Validate the other form fields
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Update the blog with the new data
        $blog->title = $request->input('title');
        $blog->description = $request->input('description');

        // Save the new zip file if the user is updating it
        if ($updateZipFile) {
            // Save the uploaded zip file to the server
            $directoryPath = str_replace('storage', 'public', $blog->zip_file);
            Storage::deleteDirectory($directoryPath);
            
           $zipFile = $request->file('zipFile');
            $zipFileName = uniqid() . '.' .$zipFile->getClientOriginalName();
            $zipFile->storeAs('blogs', $zipFileName);

            // Extract the contents of the zip file and save the necessary files
            $zip = new \ZipArchive;
            if ($zip->open(storage_path('app/blogs/'.$zipFileName)) === TRUE) {
                // Create a new directory for the extracted files
                $directory = 'public/blogs/'. $zipFileName;
                Storage::makeDirectory($directory);

                // Extract the files to the new directory
                $zip->extractTo(storage_path('app/'.$directory));
                $zip->close();

                // Find the path to the index.html file
                $indexHtmlPath = '';
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(storage_path('app/'.$directory)));
                foreach ($iterator as $file) {
                    if ($file->getFilename() === 'index.html') {
                        $indexHtmlPath = $file->getPath().'/index.html';
                        break;
                    }
                }
                $indexHtmlPath = str_replace(storage_path('app/'),'', $indexHtmlPath);

                // Save the URL of the directory and the path to the index.html file to the blog model
                $blog->zip_file = Storage::url($directory);
                $blog->index_html_path = Storage::url($indexHtmlPath);
                $blog->active = false;
            } else {
                // Handle the case where the zip file could not be opened
            }
        }

                // Save the changes to the database
                $blog->save();

                // Return a success response
                return response()->json(['message'=>'Your blog has been updated.'], 200);
            }
            public function deleteTheBlog(Request $request)
            {
                $blog = Blog::where('id', $request->input('id'))
                ->where('user_id', Auth::id())
                ->first();
                $directoryPath = str_replace('storage', 'public', $blog->zip_file);
                Storage::deleteDirectory($directoryPath);
                $blog->delete();
                return response()->json(['message' => 'Blog deleted successfully.']);
            }

}
