<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $path = Storage::disk('data')->get('dishes.json');

        $data = json_decode($path, true);
        $dishes = collect($data['dishes']);

        $restaurants = $dishes->pluck('restaurant')->unique();

        $meals = ["breakfast", "lunch", "dinner"];
        // dd($dishes);
        return view('order', compact(['dishes', 'restaurants', 'meals']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getDishes(Request $request)
    {
        $path = Storage::disk('data')->get('dishes.json');

        $data = json_decode($path, true);
        $dishes = collect($data['dishes'])->filter(function($item) use($request){
            return $item['restaurant'] == $request->restaurant && in_array($request->meal, $item['availableMeals']);
        });
        return response()->json(['dishes' => $dishes->values()], 200);
    }

}
