@extends('layouts.master')

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('backend.edited') }}" method="post" class="form-horizontal" enctype="multipart/form-data" id="edit-profile-form">
                            @csrf
                            <input type="hidden" value="{{ $user->id }}" name="id">
                            <div class="card">
                                <div class="card-header">
                                    Showing data:
                                    <strong>{{ $user->name }} {{ $user->surname }}</strong>

                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-name" class=" form-control-label">Name</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="text" id="input-name" name="name"
                                                   placeholder="Name"
                                                   class="form-control" value="{{ $user->name }}">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-surname" class=" form-control-label">Surname</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="text" id="input-surname" name="surname"
                                                   placeholder="Surname"
                                                   class="form-control" value="{{ $user->surname }}">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-email" class=" form-control-label">Email</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="email" id="input-email" name="email"
                                                   placeholder="Email"
                                                   class="form-control" value="{{ $user->email }}" disabled>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-phone" class=" form-control-label">Phone</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="text" id="input-phone" name="phone"
                                                   placeholder="Phone"
                                                   class="form-control" value="{{ $user->phone }}">
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-profile" class=" form-control-label">Profile</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="file" name="profile" class="form-control">
                                        @if($user->profile != null)
                                            <div class="image">
                                                <img
                                                    src="{{ asset('images/icon/'.$user->profile) }}"
                                                    alt="{{ $user->name }} {{ $user->surname }}" width="120px" height="120px"/>
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                    <hr/>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success btn-sm" id="submit"><i class="fa fa-dot-circle-o"></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
@endsection
@section('script_ready')
    <script type="text/javascript">
        $(function (){
            $("#edit-profile-form").validate({
                errorClass: "is-invalid",
                validClass: "is-valid",
                focusInvalid: true,
                rules: {
                    name: "required",
                    surname: "required",
                },
                messages: {
                    name: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                    surname: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                },
                submitHandler: function(form) {
                    var loading = new Loading();
                    form.submit();
                }
            });
        });
    </script>
@endsection
