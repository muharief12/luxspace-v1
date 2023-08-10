<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request) {

        // set konfigurasi midtrans
        Config::$serverKey = config('MIDTRANS_SERVER_KEY');
        Config::$isProduction = config('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = config('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = config('MIDTRANS_IS_3DS');

        // Buat instance midtrans for notifications
        $notification = new Notification();

        // Assigning variable for coding easier
        $status = $notification->transaction_status;
        $type = $notification->transaction_type;
        $fraud = $notification->fraud;
        $order_id = $notification->order_id;


        // Get transaction ID by 'LUX-XXXXXX'
        $order = explode('-', $order_id);

        // Cari transactions by ID
        $transaction = Transaction::findOrFail($order[1]);

        // Handle notifications status midtrans
        if ($status == 'capture') {
            if($type == 'credit_card') {
                if($fraud == 'challenge') {
                    $transaction->status = 'PENDING';
                }
                else {
                    $transaction->status = 'SUCCESS';
                }
            }
        }

        else if ($status == 'settlement') {
            $transaction->status = 'SUCCESS';
        }

        else if ($status == 'pending') {
            $transaction->status = 'PENDING';
        }

        else if ($status == 'deny') {
            $transaction->status = 'PENDING';
        }

        else if ($status == 'expire') {
            $transaction->status = 'CANCELLED';
        }

        else if ($status == 'cancel') {
            $transaction->status = 'CANCELLED';
        }
        // Simpan transaction
        $transaction->save();

        // Return response for midtrans transaction
        return response()->json([
            'meta' => [
                'code' => 200,
                'message' => 'Midtrans Notification Success',
            ]
        ]);

    }
}
