<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;

class DashboardController extends Controller
{
    public function index()
    {
        // Semua order untuk Live Order Management
        $orders = Order::with('details.menu')
        ->when(request('search'), function ($q) {
            $q->where(function($query){
                $query->where('order_number','like','%'.request('search').'%')
                    ->orWhere('customer_name','like','%'.request('search').'%');
            });
        })

        ->when(request('status'), function ($q) {
            $q->where('status', request('status'));
        })

        ->latest()
        ->get();

        // Semua menu untuk POS Manual
        $menus = Menu::all();

        // ================= METRIC =================
        $totalOrders = Order::count();
        $revenue = Order::where('status','Completed')
                        ->sum('total');

        $pending = Order::where('status','Pending')
                        ->count();

        $processing = Order::where('status','Processing')
                           ->count();

        $ready = Order::where('status','Ready')
                      ->count();

        $completed = Order::where('status','Completed')
                          ->count();

        $cancel = Order::where('status','Cancelled')
                       ->count();

        return view('admin.dashboard.index', compact(
            'orders',
            'menus',
            'totalOrders',
            'revenue',
            'pending',
            'processing',
            'ready',
            'completed',
            'cancel'
        ));
    }
}