@extends('layouts.master')

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
@endsection

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">@if(auth()->user()->hasRole('admin')) Channels List @else My
                                Channel @endif</h2>
                            @if(auth()->user()->hasPermission('create.store'))
                                <a class="au-btn au-btn-icon au-btn--blue" href="{{ route('backend.create_channel') }}">
                                    <i class="zmdi zmdi-plus"></i>Create
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="clearfix mb-4"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive table--no-card m-b-40">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($stores as $store)
                                    <tr class="clickable-row"
                                        data-href="{{ route('backend.store.show',$store->id) }}">
                                        <td>{{ $store->name }}</td>
                                        <td>{{ $store->description }}</td>
                                        <td>{{ $store->address }} {{ $store->district }} {{ $store->amphur }} {{ $store->province }} {{ $store->zipcode }}</td>
                                        <td><i class="fa fa-phone"></i> {{ $store->phone }} <br/><i
                                                class="fa fa-envelope"></i> {{ $store->email }} @if($store->line != null)
                                                <br/><i class="fa fa-line"></i> {{ $store->line }}@endif</td>
                                        <td>
                                            @if($store->status == '0')
                                                <div class="text-danger font-weight-bold">กำลังพิจารณา</div> @else
                                                <div class="text-success font-weight-bold">เผยแพร่</div> @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center"> ไม่พบข้อมูล</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            {!! $stores->render() !!}
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
@endsection
@section('script_ready')
    <script type="text/javascript">
        $(function () {
            $(".clickable-row").click(function () {
                new Loading();
                window.location = $(this).data("href");
            });
        });
    </script>
@endsection
