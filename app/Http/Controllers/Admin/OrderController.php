<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AuditLog;
use App\Models\Customer;

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
            'status' => 'required'
        ]);
        // Saat Kitchen mulai memasak
        if ($request->status == 'Processing' && $order->cooking_started_at == null) {
            $order->cooking_started_at = now();
        }
        // Saat Kitchen selesai memasak
        if ($request->status == 'Ready') {
            $order->ready_at = now();

        }
        $order->status = $request->status;
        $order->save();
        AuditLog::create([
            'user' => 'Admin',
            'activity' => 'Mengubah status Order ' . $order->order_number . ' menjadi ' . $order->status,
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
        AuditLog::create([
            'user' => 'Admin',
            'activity' => 'Melakukan refund Order ' . $order->order_number,
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
        AuditLog::create([
            'user' => 'Admin',
            'activity' => 'Membatalkan Order ' . $order->order_number,
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
        AuditLog::create([
            'user' => 'Admin',
            'activity' => 'Melakukan void Order ' . $order->order_number,
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
                'Refunded',
                'void'
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

    public function kitchenTicket(Order $order)
    {
        $order->load('details.menu');
        $pdf = Pdf::loadView(
            'admin.orders.kitchen-ticket',
            compact('order')
        );
        return $pdf->download(
            'kitchen-ticket-'.$order->order_number.'.pdf'
        );
    }
}