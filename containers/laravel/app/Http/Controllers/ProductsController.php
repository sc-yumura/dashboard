<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
    function index(Request $request)
    {
        return response()->json(['message' => 'Products index']);
    }
}
