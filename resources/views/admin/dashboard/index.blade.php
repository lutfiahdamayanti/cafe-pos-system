@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4 fw-bold">

        Dashboard

    </h2>

    <div class="row g-4">

        <div class="col-md-3">

            <div class="dashboard-card">

                <h5>Revenue</h5>

                <h3>Rp 0</h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card">

                <h5>Orders</h5>

                <h3>0</h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card">

                <h5>Pending</h5>

                <h3>0</h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="dashboard-card">

                <h5>Completed</h5>

                <h3>0</h3>

            </div>

        </div>

    </div>

</div>

@endsection