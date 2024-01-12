<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceCreated;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class CreateInvoiceController extends Controller
{
    //

    public function store(Request $request)
    {
        // Validate incoming request data
        $data = $request->validate([
            'invoice_title' => 'required|string',
            'invoice_number' => 'required|integer',
            'due_date' => 'required|string',
            'additional_note' => 'required|string',
            'status' => 'required|string',
            'items' => 'required|array|min:1', // Validate items as an array and at least one item
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.item_wise_discount' => 'required|integer',
            'items.*.unit_price' => 'required|string',
//customer
            'customer_id' => 'required|exists:customers,id',

        ]);

        // Create the invoice
        $invoice = Invoice::create([
            'invoice_title' => $data['invoice_title'],
            'invoice_number' => $data['invoice_number'],
            'due_date' => $data['due_date'],
            'additional_note' => $data['additional_note'],
            'status' => $data['status'],
            'customer_id' => $data['customer_id'], // Add customer_id to the invoice creation
        ]);

        // Add items to the invoice
        foreach ($data['items'] as $itemData) {
            $item = new Items([
                'item_name' => $itemData['item_name'],
                'quantity' => $itemData['quantity'],
                'item_wise_discount' => $itemData['item_wise_discount'],
                'unit_price' => $itemData['unit_price'],
            ]);

            // Calculate final cost for the item
            $finalCost = $itemData['quantity'] * ($itemData['unit_price'] - ($itemData['unit_price'] * $itemData['item_wise_discount'] / 100));

//            $item->final_cost = $finalCost; // Assuming there's a 'final_cost' column in 'items' table

            $invoice->items()->save($item);
        }
//// Send email to the customer
//        $customer = Customer::find($data['customer_id']);
//        $invoiceTitle = $data['invoice_title'];

        $data["email"] = "hasanthamadushan32@gmail.com";
        $data["title"] = "Invoice Details";
        $data["content"] = $itemData['quantity'];


        Mail::send('mail.Test_mail', $data, function($message) use ($data) {
            $message->to($data["email"])
                ->subject($data["title"]);
        });

        return response()->json(['message' => 'Invoice created with items'], 201);
    }
}
