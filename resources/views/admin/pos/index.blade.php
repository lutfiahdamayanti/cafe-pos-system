@extends('layouts.admin')
@section('title','Pesanan Baru')
@section('content')

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header"><h4>Daftar Menu</h4></div>
            <div class="card-body">
                <div class="row">
                    @foreach($menus as $menu)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 pos-menu-card">
                            <img src="{{ asset('images/'.$menu->image) }}" class="card-img-top pos-menu-image">
                            <div class="card-body d-flex flex-column">
                                <div class="pos-menu-content">
                                    <h5>{{ $menu->name }}</h5>
                                    <div class="mt-3 pos-option-area">
                                        @php
                                            $sizeOption=$menu->options->where('name','Ukuran')->first();
                                        @endphp
                                        @if($sizeOption)
                                        <label class="fw-bold mb-1">Ukuran</label>
                                        <select class="form-select size-select mb-2">
                                            <option value="">-- Pilih --</option>
                                            @foreach($sizeOption->values as $value)
                                            <option value="{{ $value->value }}" data-price="{{ $value->extra_price }}">
                                                {{ $value->value }}
                                                @if($value->extra_price > 0)
                                                    (+Rp {{ number_format($value->extra_price,0,',','.') }})
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                        @endif
                                        @foreach($menu->options as $option)
                                        <label class="fw-bold mt-2">{{ $option->name }}</label>
                                        <select class="form-select option-select mb-2">
                                            <option value="">-- Pilih --</option>
                                            @foreach($option->values as $value)
                                            <option value="{{ $value->id }}"
                                                data-name="{{ $option->name }}"
                                                data-value="{{ $value->value }}"
                                                data-price="{{ $value->extra_price }}">
                                                {{ $value->value }}
                                                @if($value->extra_price>0)
                                                    (+Rp {{ number_format($value->extra_price,0,',','.') }})
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                        @endforeach
                                        <textarea class="form-control note mt-2" placeholder="Catatan"></textarea>
                                    </div>
                                    <button type="button"
                                        class="btn btn-success w-100 mt-3 add-menu"
                                        data-id="{{ $menu->id }}"
                                        data-name="{{ $menu->name }}"
                                        data-price="{{ $menu->price }}">
                                        Tambahkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header"><h4>Pesanan</h4></div>
            <div class="card-body">
                <form action="{{ route('admin.pos.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Pelanggan</label>
                        <select id="customer_select" class="form-select">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}"
                                data-name="{{ $customer->name }}"
                                data-phone="{{ $customer->phone }}">
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                            @endforeach
                            <option value="new">Pelanggan Baru</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nomor HP</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Pesanan</label>
                        <select name="visit_type" id="visit_type" class="form-select" required>
                            <option value="">-- Pilih Jenis Pesanan --</option>
                            <option value="Dine In">Dine In</option>
                            <option value="Pickup">Pickup</option>
                            <option value="Delivery">Delivery</option>
                            <option value="Pre-order">Pre-order</option>
                        </select>
                    </div>
                    <div class="mb-3" id="table-wrapper">
                        <label>Nomor Meja</label>
                        <select name="table_number" id="table_number" class="form-select">
                            <option value="">-- Pilih Meja --</option>
                            @foreach($tables as $table)
                            <option value="{{ $table }}">{{ $table }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Pembayaran</label>
                        <div id="payment-list">
                            <div class="payment-item mb-2">
                                <div class="row g-2">
                                    <div class="col-7">
                                        <select name="payments[0][method]" class="form-select payment-method">
                                            <option value="Cash">Cash</option>
                                            <option value="QRIS">QRIS</option>
                                            <option value="E-Wallet">E-Wallet</option>
                                            <option value="Virtual Account">Virtual Account</option>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <input type="number"
                                            name="payments[0][amount]"
                                            class="form-control payment-amount"
                                            placeholder="Nominal"
                                            min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button  type="button" class="btn btn-primary btn-sm mt-2" id="split-bill-btn">Split Bill</button>
                        <div id="split-bill-box" class="mt-3" style="display:none">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <label class="fw-bold">Jumlah Orang</label>
                                    <div class="input-group mt-2">
                                        <input type="number" id="split-count" class="form-control" min="2" value="2">
                                        <button type="button" class="btn btn-primary" id="calculate-split">Bagi</button>
                                    </div>
                                    <div id="split-result" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-warning btn-sm mt-2" id="add-payment">+ Tambah Pembayaran</button>
                        <div class="mt-3">
                            <small class="text-muted">Total Dibayar</small>
                            <div id="payment-total" class="fw-bold">Rp 0</div>
                        </div>
                        <div id="payment-status" class="small mt-1"></div>
                        <button type="button" class="btn btn-dark btn-sm mt-2" id="open-cash-drawer">🗄️ Buka Laci Kasir</button>
                        <div id="cash-drawer-status" class="small text-success mt-2"></div>
                    </div>
                    <hr>
                    <div id="cart-items"><p class="text-muted">Belum ada menu.</p></div>
                    <div id="hidden-cart"></div>
                    <hr>
                    <div class="mb-3">
                        <label>Diskon</label>
                        <input type="number"
                            name="discount"
                            id="discount"
                            class="form-control"
                            value="0"
                            min="0"
                            placeholder="Masukkan diskon"
                            oninput="renderCart()">
                    </div>
                    <div id="grandTotal">Rp 0</div>
                    <button type="submit" class="btn btn-success w-100 mt-3">Simpan Pesanan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const $=id=>document.getElementById(id);
const visitType=$('visit_type'),tableWrapper=$('table-wrapper'),tableNumber=$('table_number');
const customerSelect=$('customer_select'),customerName=$('customer_name'),customerPhone=$('phone');
let cart=[],paymentIndex=1,splitBillActive=false;

function updateTableField(){
    const dine=visitType.value==='Dine In';
    tableWrapper.style.display=dine?'block':'none';
    tableNumber.required=dine;
    if(!dine)tableNumber.value='';
}
visitType.addEventListener('change',updateTableField);
updateTableField();

customerSelect.addEventListener('change',function(){
    const selected=this.options[this.selectedIndex];
    if(this.value==='new'){
        customerName.value='';
        customerPhone.value='';
        customerName.focus();
    }else if(this.value){
        customerName.value=selected.dataset.name;
        customerPhone.value=selected.dataset.phone;
    }else{
        customerName.value='';
        customerPhone.value='';
    }
});

function renderCart(){
    let html='',hidden='',subtotal=0;

    cart.forEach((item,index)=>{
        subtotal+=item.price*item.qty;

        html+=`
        <div class="border-bottom py-3">
            <div class="d-flex justify-content-between align-items-start">
                <strong>${item.name}</strong>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">Hapus</button>
            </div>

            <small>Ukuran : ${item.size}</small>
            ${item.options.map(o=>`<br><small>• ${o.name} : ${o.value}</small>`).join('')}
            ${item.note?`<br><small><b>Catatan :</b> ${item.note}</small>`:''}

            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="decreaseQty(${index})">−</button>
                    <span>${item.qty}</span>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="increaseQty(${index})">+</button>
                </div>
                <strong>Rp ${(item.price*item.qty).toLocaleString()}</strong>
            </div>
        </div>`;

        hidden+=`
        <input type="hidden" name="menus[${index}][id]" value="${item.id}">
        <input type="hidden" name="menus[${index}][qty]" value="${item.qty}">
        <input type="hidden" name="menus[${index}][size]" value="${item.size}">
        <input type="hidden" name="menus[${index}][note]" value="${item.note}">`;

        item.options.forEach(option=>{
            hidden+=`<input type="hidden" name="menus[${index}][options][]" value="${option.id}">`;
        });
    });

    if(!cart.length)html='<p class="text-muted">Belum ada menu.</p>';

    let discount=parseInt($('discount').value)||0;
    if(discount>subtotal){
        discount=subtotal;
        $('discount').value=discount;
    }

    let afterDiscount=subtotal-discount;
    let tax=afterDiscount*0.11;
    let service=3000;
    let total=afterDiscount+tax+service;

    $('cart-items').innerHTML=html;
    $('hidden-cart').innerHTML=hidden;
    $('grandTotal').innerHTML=`
        Subtotal : Rp ${subtotal.toLocaleString()}<br>
        Diskon : Rp ${discount.toLocaleString()}<br>
        PPN 11% : Rp ${tax.toLocaleString()}<br>
        Service : Rp ${service.toLocaleString()}<hr>
        <h5>Total : Rp ${total.toLocaleString()}</h5>`;
}

function increaseQty(i){cart[i].qty++;renderCart()}
function decreaseQty(i){
    cart[i].qty>1?cart[i].qty--:cart.splice(i,1);
    renderCart();
}
function removeItem(i){cart.splice(i,1);renderCart()}

document.querySelectorAll('.add-menu').forEach(btn=>{
    btn.addEventListener('click',function(){
        const card=this.closest('.card-body');
        const size=card.querySelector('.size-select');
        const sizeValue=size?size.value:'-';
        let price=parseInt(this.dataset.price),options=[];

        card.querySelectorAll('.option-select').forEach(select=>{
            if(select.value){
                const opt=select.options[select.selectedIndex];
                price+=parseInt(opt.dataset.price);
                options.push({
                    id:select.value,
                    name:opt.dataset.name,
                    value:opt.dataset.value
                });
            }
        });

        const note=card.querySelector('.note').value;
        const id=this.dataset.id;
        const name=this.dataset.name;

        const found=cart.find(item=>
            item.id==id &&
            item.size==sizeValue &&
            JSON.stringify(item.options)==JSON.stringify(options) &&
            item.note==note
        );

        found?found.qty++:cart.push({
            id,name,price,qty:1,size:sizeValue,options,note
        });

        renderCart();
    });
});

function updatePaymentTotal(){
    let total=0;
    document.querySelectorAll('.payment-amount').forEach(input=>{
        total+=parseInt(input.value)||0;
    });
    $('payment-total').innerText='Rp '+total.toLocaleString('id-ID');
}

$('add-payment').addEventListener('click',()=>{
    $('payment-list').insertAdjacentHTML('beforeend',`
        <div class="payment-item mb-2">
            <div class="row g-2">
                <div class="col-7">
                    <select name="payments[${paymentIndex}][method]" class="form-select payment-method">
                        <option value="Cash">Cash</option>
                        <option value="QRIS">QRIS</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Virtual Account">Virtual Account</option>
                    </select>
                </div>
                <div class="col-5"><input type="number" name="payments[${paymentIndex}][amount]" class="form-control payment-amount" placeholder="Nominal" min="0"></div>
            </div>
        </div>
    `);
    paymentIndex++;
    updatePaymentTotal();
});

document.addEventListener('input',e=>{
    if(e.target.classList.contains('payment-amount'))updatePaymentTotal();
});

$('split-bill-btn').addEventListener('click',()=>{
    const box=$('split-bill-box');
    const list=$('payment-list');
    const add=$('add-payment');

    splitBillActive=!splitBillActive;
    box.style.display=splitBillActive?'block':'none';
    list.style.display=splitBillActive?'none':'block';
    add.style.display=splitBillActive?'none':'inline-block';

    if(!splitBillActive){
        $('split-result').innerHTML='';
        list.innerHTML=`
            <div class="payment-item mb-2">
                <div class="row g-2">
                    <div class="col-7">
                        <select name="payments[0][method]" class="form-select payment-method">
                            <option value="Cash">Cash</option>
                            <option value="QRIS">QRIS</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Virtual Account">Virtual Account</option>
                        </select>
                    </div>
                    <div class="col-5"><input type="number" name="payments[0][amount]" class="form-control payment-amount" placeholder="Nominal" min="0"></div>
                </div>
            </div>`;
        paymentIndex=1;
        updatePaymentTotal();
    }
});

$('calculate-split').addEventListener('click',()=>{
    const count=parseInt($('split-count').value)||0;

    if(count<2){
        alert('Jumlah orang minimal 2.');
        return;
    }

    let subtotal=cart.reduce((sum,item)=>sum+(item.price*item.qty),0);

    if(subtotal<=0){
        alert('Tambahkan menu terlebih dahulu.');
        return;
    }

    let discount=parseInt($('discount').value)||0;

    if(discount>subtotal){
        discount=subtotal;
        $('discount').value=discount;
    }

    const afterDiscount=subtotal-discount;
    const tax=Math.round(afterDiscount*0.11);
    const service=3000;
    const total=afterDiscount+tax+service;
    const baseAmount=Math.floor(total/count);
    const remainder=total%count;

    $('split-result').innerHTML=`
        <div class="alert alert-info">
            <strong>Total Pesanan:</strong>
            Rp ${total.toLocaleString('id-ID')}
        </div>`;

    $('payment-list').innerHTML='';

    for(let i=0;i<count;i++){
        let amount=baseAmount+(i===0?remainder:0);

        $('split-result').insertAdjacentHTML('beforeend',`
            <div class="row g-2 mb-2">
                <div class="col-3"><input type="text" class="form-control" value="Orang ${i+1}" readonly></div>
                <div class="col-5">
                    <select name="payments[${i}][method]" class="form-select payment-method">
                        <option value="Cash">Cash</option>
                        <option value="QRIS">QRIS</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Virtual Account">Virtual Account</option>
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" name="payments[${i}][amount]" class="form-control payment-amount" value="${amount}" min="0">
                </div>
            </div>`);
    }

    paymentIndex=count;
    updatePaymentTotal();
});

$('open-cash-drawer').addEventListener('click',()=>{
    const status=$('cash-drawer-status');
    status.innerText='Laci kasir dibuka.';
    setTimeout(()=>status.innerText='',3000);
});
</script>
@endpush
@endsection