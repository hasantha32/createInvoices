<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Models\Customer;
use App\Models\MerchantUser;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //

    public function store(CustomerRequest $request)
    {
        $data = $request->all();

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

    //get all customers
    public function getAllCustomers()
    {
        $customers = Customer::all();

        return response()->json($customers);
    }

    //get all blacklisted customers
//    public function getAllBlacklistedCustomers()
//    {
//        $blacklistedCustomers = Customer::where('blacklisted', true)->get();
//
//        return response()->json($blacklistedCustomers);
//    }
//
//    //get all non blacklisted customers
//    public function getAllNonBlacklistedCustomers()
//    {
//        $nonBlacklistedCustomers = Customer::where('blacklisted', false)->get();
//
//        return response()->json($nonBlacklistedCustomers);
//    }
    public function getCustomersFilterByBlacklistORNot(Request $request)
    {
        $isBlacklisted = $request->query('Blacklist');

// Ensure boolean values for the parameters
        $isBlacklisted = filter_var($isBlacklisted, FILTER_VALIDATE_BOOLEAN);

// Build the query based on the provided parameters
        $query = Customer::query();

        if ($isBlacklisted !== null) {
            $query->where('blacklisted', $isBlacklisted)->get();
        }

// Retrieve the customers based on the filtering conditions
        $customers = $query->get();

        return response()->json($customers);
    }
    // Update a customer
    public function update(Request $request, $id)
    {
        $customer = Customer::where('id',$id)->first();

        // Check if the customer exists
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        // Update the customer data
        $customer->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
        ]);

        // Return a success response
        return response()->json(['message' => 'Customer updated successfully', 'customer' => $customer], 201);
    }

}
