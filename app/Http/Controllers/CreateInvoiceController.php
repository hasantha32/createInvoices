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
            'status' => 'string', // Remove 'required'
//            'status' => 'required|string',
            'items' => 'required|array|min:1', // Validate items as an array and at least one item
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.item_wise_discount' => 'required|integer',
            'items.*.unit_price' => 'required|string',
//customer
            'customer_id' => 'required|exists:customers,id',

        ]);
        $totalFinalCost = 0;
        $status='Pending';
        $itemCount=0;

        // Create the invoice
        $invoice = Invoice::create([
            'invoice_title' => $data['invoice_title'],
            'invoice_number' => $data['invoice_number'],
            'due_date' => $data['due_date'],
            'additional_note' => $data['additional_note'],
            'status' => $status,
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

            $item->final_cost = $finalCost;

            // Add the final cost of the items to the total final cost for the invoice
            $totalFinalCost += $finalCost;
//            dump($totalFinalCost);
            Invoice::where('id',$invoice->id)->update(['totalFinalCost' => $totalFinalCost]);

            $invoice->items()->save($item);
            // Increment item count
            $itemCount++;
        }

        // Fetch customer details
        $customer = Customer::find($data['customer_id']);

//// Send email to the customer
        $data["email"] = "hasanthamadushan32@gmail.com";
//        $data["email"] = $customer->email;
        $data["customer_name"] =$customer->first_name;
        $data["invoice_number"] =$data['invoice_number'];
//        $data["date_of_transaction"] = $data
        $data["Quantity"] = $itemCount;
        $data["Transaction_amount"] = $totalFinalCost;
//        $data["Invoice for "+ $invoice->invoice_number];
        $data["title"] = "Invoice for ".$invoice->invoice_number;

        Mail::send('mail.Test_mail', $data, function($message) use ($data) {
            $message->to($data["email"])
                ->subject($data["title"]);
        });

        return response()->json(['message' => 'Invoice created with items'], 201);
    }
}
