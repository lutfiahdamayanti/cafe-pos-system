<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('details.menu');

        if($request->status){

            $query->where('status',$request->status);

        }

        if($request->search){

            $query->where(function($q) use($request){

                $q->where('order_number','LIKE','%'.$request->search.'%')
                  ->orWhere('customer_name','LIKE','%'.$request->search.'%');

            });

        }

        $orders = $query->latest()->get();

        return view('admin.orders.index',compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('details.menu');

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'=>'required'
        ]);

        $order->update([
            'status'=>$request->status
        ]);

        return back()->with(
            'success',
            'Status berhasil diubah.'
        );
    }

    public function refund(Request $request, Order $order)
    {
    $request->validate([

        'refund_reason'=>'required',

        'refund_amount'=>'required|numeric'

    ]);

    $order->update([

        'status'=>'Refund',

        'refund_reason'=>$request->refund_reason,

        'refund_amount'=>$request->refund_amount

    ]);

    return back()->with(
        'success',
        'Refund berhasil.'
    );
    }

    public function cancel(Request $request, Order $order)
    {
    $request->validate([

        'cancel_reason'=>'required'

    ]);

    $order->update([

        'status'=>'Cancelled',

        'cancel_reason'=>$request->cancel_reason

    ]);

    return back()->with(
        'success',
        'Pesanan dibatalkan.'
    );
    }

    public function void(Request $request, Order $order)
    {
    $request->validate([

        'cancel_reason'=>'required'

    ]);

    $order->update([

        'status'=>'Void',

        'cancel_reason'=>$request->cancel_reason

    ]);

    return back()->with(
        'success',
        'Pesanan berhasil di-void.'
    );
    }

    public function history()
    {
    $orders = Order::with('details.menu')

        ->whereIn('status',[
            'Completed',
            'Cancelled',
            'Refunded'
        ])

        ->latest()

        ->get();

    return view(
        'admin.history.index',
        compact('orders')
    );
    }

    public function receipt(Order $order)
    {
    $order->load('details.menu');


    $pdf = Pdf::loadView(
        'admin.orders.receipt',
        compact('order')
    );


    return $pdf->download(
        'struk-'.$order->order_number.'.pdf'
    );
    }
}