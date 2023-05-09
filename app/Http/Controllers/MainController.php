<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Category;
use App\models\Country;
use App\models\Service;
use App\models\City;
class MainController extends Controller
{
    public function fetchData()
    {
        $Categories = Category::all();
        $Countries = Country::all();   
        $Services = Service::all();
        $Cities = City::all();
        return response()->json([
            'Categories'=>$Categories,
            'Countries'=>$Countries,
            'AllServices' => $Services,
            'Cities' => $Cities],200);
    }
    public function showService($id,$title,$description)
    {
        return view('Service')->with(['id'=>$id, 'title'=>$title,'description'=>$description]);
    }
}
