<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'invoice_title' => 'required|string',
            'invoice_number' => 'required|integer',
            'due_date' => 'required|string',
            'additional_note' => 'nullable|string',//additional_not can be null
            'status' => 'string', // Remove 'required'
//            'status' => 'required|string',
            'items' => 'required|array|min:1', // Validate items as an array and at least one item
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.item_wise_discount' => 'required|integer',
            'items.*.unit_price' => 'required|string',
//customer
            'customer_id' => 'required|exists:customers,id',
        ];
    }
}
