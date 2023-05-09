<?php

namespace App\Http\Controllers;
use App\models\Blog;
use App\models\City;
use App\models\Service;
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
        $cities = City::all();
        $data = [
            'categories' => $categories,
            'cities' => $cities
        ];
        return $data;
    }
    public function storeService(Request $request)
        {
            //compteur de nombre de services
            $count = DB::table('services')->where("user_id",Auth::user()->id)
            ->count() ;
            if($count > 10)
            {
                return response()->json(['error' => 'You have exceeded the maximum number of services.'], 422);
            }
            

        // validate form data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'city' => 'required|string',
            'country_id' => 'required',
            'file' => 'required|image|max:2048', // Limit to 2MB,
            'category' => 'required|string',
            'city_id' => 'required|integer'

        ]);
        $city_id_final = $validatedData['city_id'];
        $cat_id_final = $validatedData['category_id'];

         // Get the uploaded file
         $file = $request->file('file');

         // Generate a custom file name
         $fileName = 'service_image_'.uniqid().'.'.$file->getClientOriginalExtension();
 
         // Store the file in the storage/app/public/service_image directory
         $file->storeAs('public/service_image', $fileName);
 
         // Save the file path in the database or do whatever you need to do with it
         $filePath = 'storage/service_image/'.$fileName;

         //checking if a new city being suggested
        if($validatedData['city_id'] == -1 && $validatedData['city'])
        {
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
        $city_id_final = $cityId;
        }
        //checking for a suggested category
        if($validatedData['category_id'] == -1 && $validatedData['category'])
        {
             // insert category in categories table
        $category = DB::table('categories')->where('name', '=', $validatedData['category'])
        
        ->first();
        if(!$category)
        {
            $newcategoryid = DB::table('categories')->insertGetId([
                "name" => $validatedData['category'],
            ]);
            $catId = $newcategoryid;
        }
        else{
            // retrieve city id
        $catId = $category->id;
        }
        $cat_id_final = $catId;
        }
       

       
        // insert service in services table
        $service = DB::table("services")->insert([
            "title" => $validatedData['title'],
            "description" => $validatedData['description'],
            "category_id" => $cat_id_final,
            "city_id" => $city_id_final,
            "country_id" =>$validatedData['country_id'],
            "file" => $filePath,
            "user_id" => Auth::user()->id
        ]);

        return response()->json(['success'  => "Service added successfully"],200);
        }

        //get services for a user
        public function GetUserServices()
        {
            
            $services = DB::table("services")->where("user_id", "=", Auth::user()->id)
            ->join("cities","services.city_id","=","cities.id")
            ->join("countries","services.country_id","=","countries.id")
            ->join("categories","services.category_id","=","categories.id")
            ->select("services.*","categories.name as catname","cities.name as cname","countries.name as countname")
            ->get();
            return $services;
        }
        public function deleteservice($id)
        {
           $service = Service::where('user_id',Auth::id())->find($id);
           $file_path = str_replace('storage','public',$service->file);
           
           if(Storage::exists($file_path))
           {
            Storage::delete($file_path);
           }
           $service->delete();
        }
        public function EditService(Request $request,$id)
        {
             // validate form data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'image|max:2048', // Limit to 2MB,
        ]);
        
            $service = Service::where('user_id',Auth::id())->find($id);
            $service->title = $request->input('title');
            $service->description = $request->input('description');

            if ($request->hasFile('image')) {
                // delete old image if it exists
                if ($service->file) {
                    $file_path = str_replace('storage','public',$service->file);
                    Storage::delete($file_path);
                }
                $image = $request->file('image');
                $imageName = 'service_image_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/service_image', $imageName);
                $service->file =  'storage/service_image/' . $imageName;
            
                
            }
            $service->save();

            return response()->json($service);
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
            public function GetServiceView(Request $request)
            {
                $service = Service::with('city', 'country')
                ->where('user_id',Auth::id())->findOrFail($request->params['id']);
            
                return $service;
            }

}
