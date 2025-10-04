<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayController extends Controller
{
    public function paymentSuccess(Request $request)
    {
        $paymentId = $request->get('payment_id');
        return redirect()->route('category.index')
            ->with("success", "Category Updated Successfully. Payment ID: " . $paymentId);
    }
}
