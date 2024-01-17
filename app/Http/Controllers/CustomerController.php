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

    //get customer by id
    public function getById($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        return response()->json($customer);
    }


    public function addToBlacklist($id, Request $request)
    {
        $user = Customer::findOrFail($id);
        $user->blacklisted = true;
        $user->blacklist_reason_add = $request->input('reason');
        $user->save();

        return response()->json(['message' => 'User added to blacklist successfully']);
    }

    public function removeFromBlacklist($id, Request $request)
    {
        $user = Customer::findOrFail($id);

        // Check if a reason is provided in the request, and update the reason if available
        if ($request->has('reason')) {
            $user->blacklist_reason_remove = $request->input('reason');
        }

        $user->blacklisted = false;
        $user->save();

        return response()->json(['message' => 'User removed from blacklist successfully']);
    }

    public function getBlacklistStatus($id)
    {
        $user = Customer::findOrFail($id);

        return response()->json([
            'blacklisted' => $user->blacklisted,
            'blacklist_reason_add' => $user->blacklist_reason_add,
            'blacklist_reason_remove' => $user->blacklist_reason_remove,
        ]);
    }
}
