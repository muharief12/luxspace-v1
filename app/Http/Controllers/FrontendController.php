<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Midtrans\Config;
use Midtrans\Snap;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $products  = Product::with(['galleries'])->latest()->get();

        return view('pages.frontend.index', compact('products'));
    }

    public function details(Request $request, $slug)
    {
        $product = Product::with(['galleries'])->where('slug', $slug)->firstOrFail();
        $recommendations = Product::with(['galleries'])->inRandomOrder()->limit(4)->get( );

        return view('pages.frontend.details', compact('product','recommendations'));
    }

    public function cartAdd(Request $request, $id) 
    {
        Cart::create([
            'users_id' => auth()->user()->id,
            'products_id' => $id,
        ]);

        return redirect('cart');
    }

    public function cart(Request $request)
    {
        $carts = Cart::with(['product.galleries'])->where('users_id', auth()->user()->id)->get();
        return view('pages.frontend.cart', compact('carts'));
    }

    public function cartDelete(Request $request, $id)
    {
        $item = Cart::findOrFail($id);

        $item->delete();

        Return redirect('cart');
    }

    public function success(Request $request)
    {
        return view('pages.frontend.success');
    }

    public function checkout(CheckoutRequest $request)
    {
        $data = $request->all();

        // get Carts Data
        $carts = Cart::with(['product'])->where('users_id', Auth::user()->id)->get();

        // Add to transaction data
        $data['users_id'] = Auth::user()->id;
        $data['total_price'] = $carts->sum('product.price');

        // Create a transaction
        $transaction = Transaction::create($data);

        // Create a transaction item
        foreach ($carts as $cart) {
            $items[] = TransactionItem::create([
                'transactions_id' => $transaction->id,
                'users_id' => $cart->users_id,
                'products_id' => $cart->products_id,
            ]);
        }

        // Delete cart after transaction
        Cart::where('users_id', Auth::user()->id)->delete();

        // Midtrans Config
        Config::$serverKey      = config('services.midtrans.serverKey');
        // Config::$clientKey      = config('services.midtrans.clientKey');
        Config::$isProduction   = config('services.midtrans.isProduction');
        Config::$isSanitized    = config('services.midtrans.isSanitized');
        Config::$is3ds          = config('services.midtrans.is3ds');

        // Setup midtrans variables
        $midtrans = [
            'transaction_details' => [
                'order_id' => 'LUX-' . $transaction->id,
                'gross_amount' => (int) $transaction->total_price,
            ],

            'customer_details' => [
                'first_name' => $transaction->name,
                'email' => $transaction->email,
            ],
            'enabled_payments' => ['gopay','bank_transfer','credit_card'],
            'vtweb' => [],
        ];
        // Payment process
        try {
            // Get snap payment page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            // Redirect to Snap Payment Page
            return redirect($paymentUrl);

        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
}
