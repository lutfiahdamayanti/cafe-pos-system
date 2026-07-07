<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class FacilitiesController extends Controller
{
    public function index()
    {
        return view('customer.facilities');
    }
}