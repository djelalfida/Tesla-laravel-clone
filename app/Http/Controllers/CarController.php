<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Offer;
use App\Models\Feature;
use App\Models\Carmodel;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{

    function getAllCars(Request $request) {
        $offers = Offer::join('users', 'offers.seller', '=', 'users.id')->select('offers.*', 'users.email', 'users.name', 'users.photoUrl')->get();

        return response()->json($offers, 200);
    }

    function showCar(Request $request, $car) {
        $carModel = Offer::where('offerid', $car)->join('users', 'offers.seller', '=', 'users.id')->select('offers.*', 'users.email', 'users.name', 'users.photoUrl')->first();
        $featuresIds = json_decode($carModel -> featureslist);
        $features = Feature::whereIn('id', $featuresIds)->get();

        return view('car', ["car" => $carModel, "features" => $features]);
    }

    function showAddCarForm() {
        $features = Feature::all();
        return view('add-offer', ["features" => $features]);
    }

    function addCar(Request $request) {
        $results = $this -> validateOffer($request);

        $offer = new Offer;

        $offer -> featureslist = json_encode($results["features"]);
        $offer -> modelName = $results["model"];
        $offer -> typeModel = $results["type"];
        $offer -> price = $results["price"];
        $offer -> odometer = $results["odometer"];
        $offer -> accel = $results["accel"];
        $offer -> range = $results["range"];
        $offer -> topSpeed = $results["topspeed"];

        $imagesUrl = [];

        foreach ($results["carimages"] as $item) {
            $url = Cloudinary::upload($item->getRealPath())->getSecurePath();
            array_push($imagesUrl, $url);
        }

        $offer -> images = json_encode($imagesUrl);
        $offer -> year = $results["year"];
        $offer -> seller = Auth::user() -> id;

        $offer -> save();

        return redirect()->back()->with("message", "Profile successfully updated!");
    }

    function validateOffer(Request $request) {
        $rules = [
            "model" => "required|string|min:5|max:7",
            "type" => "required|string|min:5|max:27",
            "year" => "required|integer",
            "features" => "required|array",
            "price" => "required|integer",
            "odometer" => "required|integer",
            "accel" => "required|integer",
            "range" => "required|integer",
            "topspeed" => "required|integer",
            "carimages" => 'required|array',
            "carimages.*" => 'image|mimes:jpg,jpeg,png'
        ];

        return $request -> validate($rules);
    }

}
