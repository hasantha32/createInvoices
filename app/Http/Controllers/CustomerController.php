<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:customers',
            'mobile' => 'required|string',
        ]);

        $customer = Customer::create($data);

        return response()->json(['message' => 'Customer created successfully', 'customer' => $customer], 201);
    }
}
