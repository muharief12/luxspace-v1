<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MyTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            $query = Transaction::with(['user'])->where('users_id', Auth::user()->id);
            
            return DataTables::of($query)
            ->addColumn('action', function($item) {
                return '
                    <a href="'. route('dashboard.my-transaction.show', $item->id).'" class="bg-green-500 text-white text-center rounded-md px-2 py-1 mr-3">
                        View
                    </a>
                ';
            })
            ->editColumn('total_price',function($item){
                return number_format($item->total_price);
            })
            ->editColumn('status', function ($item){ 
                if ($item->status === 'PENDING' OR $item->status === 'SHIPPING') {
                   return '<button style="pointer-events:none" class="sm:rounded-lg px-4 py-2 bg-yellow-200 text-yellow-800 font-semibold my-10">'. $item->status .'</button>';
                } elseif ($item->status === 'SUCCESS' OR $item->status === 'SHIPPED') {
                    return '<button style="pointer-events:none" class="sm:rounded-lg px-4 py-2 bg-green-200 text-green-800 font-semibold my-10">'. $item->status .'</button>';
                } elseif ($item->status === 'CHALLENGE' OR $item->status === 'FAILED') {
                    return '<button style="pointer-events:none" class="sm:rounded-lg px-4 py-2 bg-red-200 text-red-800 font-semibold my-10">'. $item->status .'</button>';
                 }
                 
            })
            ->rawColumns(['status','action'])
            ->make();
        }
        return view('pages.dashboard.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $myTransaction)
    {
        if(request()->ajax())
        {
            $query = TransactionItem::with(['product'])->where('transactions_id', $myTransaction->id);
            
            return DataTables::of($query)
            ->editColumn('product.price',function($item){
                return number_format($item->product->price).'';
            })
            ->rawColumns(['action'])
            ->make();
        }
        return view('pages.dashboard.transaction.show', [
            'transaction' => $myTransaction,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
