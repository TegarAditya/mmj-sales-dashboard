<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrintController extends Controller
{
    /**
     * Display the delivery note print view for a specific delivery.
     *
     * @param  int  $id  The ID of the delivery to print.
     */
    public function deliveryPrint($id): View
    {
        $delivery = Delivery::findOrFail($id);
        $date = $delivery->date->setTimezone('Asia/Jakarta')->format('d/m/Y');
        $total = $delivery->items->sum('quantity');

        return view('prints.delivery-note', [
            'delivery' => $delivery,
            'date' => $date,
            'total' => $total,
        ]);
    }

    /**
     * Display the printable invoice view for the given invoice ID.
     *
     * @param  int  $id  The ID of the invoice to print.
     */
    public function invoicePrint($id): View
    {
        $invoice = Invoice::findOrFail($id);
        $total_price = $invoice->items->sum('total_price');
        $total_discount = $invoice->items->sum('total_discount');
        $total_due = $total_price - $total_discount;
        $date = $invoice->date->setTimezone('Asia/Jakarta')->format('d/m/Y');
        $semester = $invoice->delivery->semester->name ?? 'Semester Tidak Ditemukan';

        return view('prints.invoice', [
            'invoice' => $invoice,
            'invoice_items' => $invoice->items,
            'total_price' => $total_price,
            'total_discount' => $total_discount,
            'total_due' => $total_due,
            'date' => $date,
            'semester' => $semester,
        ]);
    }
}
