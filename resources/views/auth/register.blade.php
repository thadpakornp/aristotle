@extends('layouts.auth')

@section('content')
    <div class="page-content--bge5">
        <div class="container">
            <div class="login-wrap">
                <div class="login-content">
                    <div class="login-logo">
                        <a href="#">
                            <img src="{{ asset('images/icon/logo.png') }}" alt="CoolAdmin">
                        </a>
                    </div>
                    <div class="login-form">
                        <form id="register-form" action="{{ route('register') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="required">Email Address</label>
                                <input class="au-input au-input--full form-control" type="email" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label class="required">Password</label>
                                <input class="au-input au-input--full form-control" type="password" name="password" id="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label class="required">Confirm Password</label>
                                <input class="au-input au-input--full form-control" type="password" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                            <hr/>
                            <div class="form-group">
                                <label class="required">Name</label>
                                <input class="au-input au-input--full form-control" type="text" name="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label class="required">Surname</label>
                                <input class="au-input au-input--full form-control" type="text" name="surname" placeholder="Surname">
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="au-input au-input--full form-control" type="text" minlength="10" maxlength="10" name="phone" placeholder="Mobile Number">
                            </div>

                            <div class="form-check-inline form-check">
                                <label for="inline-checkbox1" class="form-check-label required">
                                    <input type="checkbox" id="inline-checkbox1" name="agree" class="form-check-input">Agree the terms and policy
                                </label>
                            </div>

                            <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">register</button>
                        </form>
                        <div class="register-link">
                            <p>
                                Already have account?
                                <a href="{{ route('login') }}">Sign In</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
@endsection

@section('script_ready')
    <script type="text/javascript">
        $(function () {
           $("#register-form").validate({
               errorClass: "is-invalid",
               validClass: "is-valid",
               focusInvalid: true,
               rules: {
                   email: {
                       required: true,
                       email: true
                   },
                   password: {
                       required: true,
                       minlength: 8
                   },
                   password_confirmation: {
                       equalTo : "#password"
                   },
                   name: "required",
                   surname: "required",
                   phone: {
                       minlength: 10,
                       maxlength: 10
                   },
                   agree: "required",
               },
               messages: {
                   email: {
                       required: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                       email: "รูปแบบอีเมลล์ไม่ถูกต้อง กรุณาตรวจสอบ"
                   },
                   password: {
                       required: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                       minlength: "ต้องมากกว่า 8 ตัวอักษร"
                   },
                   password_confirmation: {
                       equalTo : "รหัสผ่านไม่ตรงกัน 2 ช่อง",
                   },
                   name: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                   surname: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                   phone: {
                       minlength: "เบอร์มือถือต้องมีสิบหลักเท่านั้น",
                       maxlength: "เบอร์มือถือต้องมีสิบหลักเท่านั้น"
                   },
                   agree: "กรุณาอ่านและยอมรับเงื่อนไขการให้บริการ"
               },
               submitHandler: function(form) {
                   var loading = new Loading();
                   form.submit();
               }
           });
        });
    </script>
@endsection
