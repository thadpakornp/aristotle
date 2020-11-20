@extends('layouts.master')

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('backend.password.update') }}" method="post" class="form-horizontal" id="edit-profile-form">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    Changing Password
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-name" class=" form-control-label">Current Password</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="password" id="input-name" name="old_password"
                                                   placeholder="Current Password"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="password" class=" form-control-label">New Password</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="password" id="password" name="password"
                                                   placeholder="New Password"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-email" class=" form-control-label">Confirm Password</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="password" id="input-email" name="password_confirmation"
                                                   placeholder="Confirm Password"
                                                   class="form-control">
                                        </div>
                                    </div>
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
                    old_password: "required",
                    password: "required",
                    password_confirmation: {
                        equalTo : "#password"
                    },
                },
                messages: {
                    old_password: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                    password: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                    password_confirmation: {
                        equalTo : "รหัสผ่านไม่ตรงกัน 2 ช่อง",
                    },
                },
                submitHandler: function(form) {
                    var loading = new Loading();
                    form.submit();
                }
            });
        });
    </script>
@endsection
