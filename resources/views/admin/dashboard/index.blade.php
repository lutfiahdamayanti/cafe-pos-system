@extends('layouts.admin')
@section('title','Dashboard')
@section('content')

<div class="container-fluid dashboard-page">
    <div class="dashboard-hero mb-4">
        <div>
            <h4>Selamat Datang, {{ Auth::user()->name }} 👋</h4>
            <p>Pantau aktivitas cafe dan pesanan hari ini dengan mudah.</p>
        </div>
        <div class="dashboard-date">
            <span>Hari Ini</span>
            <strong>{{ now()->timezone('Asia/Jakarta')->format('d M Y') }}</strong>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @foreach([
            ['','🧾','Total Transaksi',$totalOrders,'Transaksi hari ini'],
            ['dashboard-revenue','💰','Penjualan Hari Ini','Rp '.number_format($revenue,0,',','.'),'Total pendapatan hari ini']
        ] as $stat)
            <div class="col-lg-6">
                <div class="dashboard-main-stat {{ $stat[0] }}">
                    <div class="dashboard-main-icon">{{ $stat[1] }}</div>
                    <div class="dashboard-main-content">
                        <span>{{ $stat[2] }}</span><strong>{{ $stat[3] }}</strong><small>{{ $stat[4] }}</small>
                    </div>
                    <div class="dashboard-stat-arrow">→</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="dashboard-heading">
        <div><h6>Status Pesanan</h6><span>Ringkasan pesanan berdasarkan status</span></div>
    </div>

    <div class="row g-3 mb-5">
        @foreach([
            ['pending','⏳','Menunggu',$pending,'Pesanan menunggu'],
            ['processing','🍳','Diproses',$processing,'Sedang diproses'],
            ['ready','🍽️','Siap Disajikan',$ready,'Siap diberikan'],
            ['completed','✓','Selesai',$completed,'Pesanan selesai'],
            ['cancelled','×','Batal',$cancel,'Pesanan dibatalkan']
        ] as $status)
            <div class="col-xl col-lg-4 col-md-4 col-sm-6">
                <div class="dashboard-status-card status-{{ $status[0] }}">
                    <div class="dashboard-status-top">
                        <div class="dashboard-status-icon">{{ $status[1] }}</div><span>{{ $status[2] }}</span>
                    </div>
                    <strong>{{ $status[3] }}</strong><small>{{ $status[4] }}</small>
                </div>
            </div>
        @endforeach
    </div>

    <div class="dashboard-orders">
        <div class="dashboard-orders-header">
            <div class="dashboard-orders-title">
                <div class="dashboard-orders-icon">🛒</div>
                <div><h5>Pesanan Aktif</h5><span>Kelola pesanan yang sedang berjalan</span></div>
            </div>
            <div class="dashboard-active-count"><strong>{{ $orders->count() }}</strong><span>Pesanan Aktif</span></div>
        </div>

        <div class="dashboard-filter">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <label>Cari Pesanan</label>
                        <div class="dashboard-input">
                            <span>⌕</span>
                            <input type="text" name="search" placeholder="Nomor order atau nama pelanggan" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label>Status</label>
                        <select name="status" class="form-select dashboard-select">
                            <option value="">Semua Status</option>
                            @foreach([
                                'Pending'=>'Menunggu','Accepted'=>'Diterima','Processing'=>'Diproses',
                                'Ready'=>'Siap Disajikan','Completed'=>'Selesai','Cancelled'=>'Dibatalkan'
                            ] as $value=>$label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex align-items-end">
                        <button class="dashboard-search-btn">Cari Pesanan <span>→</span></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table dashboard-table align-middle">
                <thead><tr><th>Pesanan</th><th>Pelanggan</th><th>Item</th><th>Catatan</th><th>Pembayaran</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><div class="dashboard-order"><strong>{{ $order->order_number }}</strong><span>Order</span></div></td>
                            <td>
                                <div class="dashboard-customer">
                                    <div class="dashboard-customer-avatar">{{ strtoupper(substr($order->customer_name,0,1)) }}</div>
                                    <div><strong>{{ $order->customer_name }}</strong><small>{{ $order->phone }}</small></div>
                                </div>
                            </td>
                            <td>
                                <div class="dashboard-items">
                                    @foreach($order->details as $detail)
                                        <div><span>{{ $detail->menu->name }}</span><b>x{{ $detail->qty }}</b></div>
                                    @endforeach
                                </div>
                            </td>
                            <td><span class="dashboard-note">{{ $order->note ?? '-' }}</span></td>
                            <td><span class="dashboard-payment">{{ $order->payment }}</span></td>
                            <td><strong class="dashboard-total">Rp {{ number_format($order->total,0,',','.') }}</strong></td>
                            <td>
                                <form action="{{ route('admin.orders.status',$order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="dashboard-status-form">
                                        <select name="status" class="form-select form-select-sm">
                                            @foreach(['Pending'=>'Menunggu','Accepted'=>'Diterima','Processing'=>'Diproses','Ready'=>'Siap Disajikan','Completed'=>'Selesai','Cancelled'=>'Dibatalkan'] as $value=>$label)
                                                <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="dashboard-update-btn">✓</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <div class="dashboard-empty">
                                <div>🛒</div><strong>Belum ada pesanan</strong><span>Belum ada pesanan aktif saat ini.</span>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection