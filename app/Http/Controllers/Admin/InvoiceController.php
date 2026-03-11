<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['order', 'customer'])->latest()->paginate(15);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['order', 'customer']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function issue(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_no' => 'required|max:50',
        ]);

        $invoice->update([
            'status' => 'issued',
            'invoice_no' => $request->invoice_no,
            'issued_at' => now(),
        ]);

        return redirect()->route('admin.invoices.index')->with('success', '发票已开具');
    }
}
