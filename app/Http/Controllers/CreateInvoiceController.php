<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\ResponseCodes;
use App\Jobs\SendInvoiceEmail;
use App\Listeners\CaptureInvoiceEmailContent;
use App\Mail\InvoiceCreated;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceCustomers;
use App\Models\InvoiceEmail;
use App\Models\Items;
use App\Models\MerchantUser;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class CreateInvoiceController extends Controller
{
    //

    public function store(Request $request)
    {
        // Validate incoming request data
      $data = $request->all();


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

            // Save item details to array
            $itemsDetails[] = [
                'item_name' => $itemData['item_name'],
                'quantity' => $itemData['quantity'],
                'item_wise_discount' => $itemData['item_wise_discount'],
                'unit_price' => $itemData['unit_price'],
                'final_cost' => $finalCost,
            ];


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
//        $data["date_of_transaction"] = now();
        $data["date_of_transaction"] = today()->toDateString();

        $data["Quantity"] = $itemCount;
        $data["Transaction_amount"] = $totalFinalCost;
//        $data["Invoice for "+ $invoice->invoice_number];
        $data["title"] = "Invoice for ".$invoice->invoice_title."-".$invoice->invoice_number;
        $data["invoice_title"] = $invoice->invoice_title;
        $data["Description_of_product"] = $invoice->additional_note;

        $data["items"] = $itemsDetails; // Pass items details to the email template

        //send the mail without using jobs & queues
//        Mail::send('mail.Test_mail', $data, function ($message) use ($data) {
//            $message->to($data["email"])
//                ->subject($data["title"]);
//        });

        //sending mail using jobs and queues
        SendInvoiceEmail::dispatch($data);

        // Save invoice emails to the new table
        InvoiceEmail::create([
            'invoice_id' => $invoice->id,
            'recipient_email' => $customer->email,
            'invoice_number' => $invoice->invoice_number,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'quantity' => $itemCount,
            'transaction_amount' => $totalFinalCost,
            'additional_note' => $invoice->additional_note,
        ]);


        return response()->json(['message' => 'Invoice created with items'], 201);
    }

    public function sendReminderForInvoices($customer_id)
    {

        $invoice = Invoice::find($customer_id);
        $customer = Customer::find($customer_id);

        $dueDateString = $invoice->due_date;
        $dueDate = new DateTime($dueDateString);
        $currentDate = new DateTime();
        $remainingDays = $dueDate->diff($currentDate)->days;
        $data["remaining_days"] = $remainingDays;

        $data["title"] = "Friendly Reminder: Invoice #" .$invoice->invoice_number;
        $data["email"] = $customer->email;
        $data["customer_name"] =$customer->first_name;
        $data["invoiceNumber"] = $invoice->invoice_number;
        $data["invoiceDate"] = $invoice->created_at;
        $data["dueDate"] = $invoice->due_date;
        $data["totalFinalCost"] =$invoice->totalFinalCost;

        Mail::send('mail.invoice-reminder', $data, function ($message) use ($data) {
            $message->to($data["email"])
                ->subject($data["title"]);
        });

        return response()->json(['message' => 'Invoice reminder to the customer'], 201);
    }

    //update the status
    public function updateStatus(Request $request, $id)
    {
            $request->all();

        $affectedRows = Invoice::where('id', $id)
            ->update(['status' => $request->input('status')]);
        if ($affectedRows == 0) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        return response()->json(['message' => 'Invoice status updated successfully'], 200);
    }



//    public function updateStatus(Request $request, $id)
//    {
//        $request->validate([
//            'status' => 'required|in:Pending,Paid,Overdue',
//        ]);
//
//        $invoice = Invoice::find($id);
//
//        if (!$invoice) {
//            return response()->json(['message' => 'Invoice not found'], 404);
//        }
//
//        $invoice->updateStatus($request->input('status'));
//
//        return response()->json(['message' => 'Invoice status updated successfully'], 200);
//
//    }


//    public function updateStatus(Request $request, $id)
//    {
//        $request->validate([
//            'status' => 'required|in:Pending,Paid,Overdue',
//        ]);
//
//        try {
//            Invoice::updateStatusById($id, $request->input('status'));
//            return response()->json(['message' => 'Invoice status updated successfully'], 200);
//        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//            return response()->json(['message' => 'Invoice not found'], 404);
//        }
//    }










//    public function getAllInvoices()
//    {
////        $invoices = Invoice::all();
//        $invoices = DB::table('invoices')
//            ->paginate(10);
//        if (!$invoices) {
//            return response()->json(['message' => 'Invoice not found'], 404);
//        }
//        return response()->json($invoices);
//    }

// Get invoices (all,active,inactive)
    public function getInvoicesFilterByActiveORInactive(Request $request)
    {
        try {
            $start = "===== InvoiceRetrieve START ======";
            $end = "===== InvoiceRetrieve END ======";
            log::info($start);


            $invoices = DB::table('invoices')
                ->join('customers', 'invoices.customer_id', '=', 'customers.id')
//                ->whereIn('invoices.customer_id', $customer)
                ->select(
                    'invoices.*',
                    DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) AS customer_full_name"),
                    'invoices.status AS invoice_status'
                )
                ->when($request->input('status'), function ($query, $status) {
                    $query->where('invoices.status', $status);
                })
                ->when($request->input('date_issued'), function ($query, $date_issued) {
                    $query->whereDate('invoices.created_at', $date_issued);
                })
                ->when($request->input('invoice_number'), function ($query, $invoice_number) {
                    $query->where('invoices.invoice_number', $invoice_number);
                })
                ->when($request->input('invoice_customer'), function ($query, $invoice_customer) {
                    $query->where('invoices.customer_id', $invoice_customer);
                })
                ->paginate($request->input('itemsPerPage', 10));

            log::info($end);
            return response()->json($invoices);

        } catch (Exception $e) {
            Log::error('Exception: ' . $e->getMessage(), ['exception' => $e]);
            log::info($end);
            return response()->json(['message' => 'Invoice not found'], 404);
        }

    }
}
