<?php

namespace App\Http\Controllers\Admin\StoreManagementSystem\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoicePrintController extends Controller
{
    public function print(Invoice $invoice)
    {
        return view('admin.store-management-system.invoice.print', compact('invoice'));
    }
}
