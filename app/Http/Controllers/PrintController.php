<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function deliveryPrint($id)
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
}
