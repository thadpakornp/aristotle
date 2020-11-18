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
                            <h2 class="title-1">Users List</h2>
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-plus"></i>add user
                            </button>
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
                                    <th>Profile</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($users as $user)
                                    <tr class="clickable-row"
                                        data-href="{{ route('backend.users.show', $user->id) }}">
                                        <td>
                                            <div class="image">
                                                <img
                                                    src="@if($user->profile == null) {{ asset('images/icon/avatar-01.jpg') }} @else {{ asset($user->profile) }} @endif"
                                                    alt="{{ $user->name }} {{ $user->surname }}"/>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->name }} {{ $user->surname }}</td>
                                        <td>{{ $user->phone }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        {!! $users->render() !!}
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