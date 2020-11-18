@extends('layouts.master')

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('backend.users.created') }}" method="post" class="form-horizontal" enctype="multipart/form-data" id="create-user-form">
                            @csrf

                            <div class="card">
                                <div class="card-header">
                                    <strong>Create user</strong>
                                    <a href="{{ route('backend.users.index') }}" class="btn btn-sm btn-secondary pull-right">Back to user</a>
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-name" class=" form-control-label">Name</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <input type="text" id="input-name" name="name"
                                                   placeholder="Name"
                                                   class="form-control">
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
                                                   class="form-control">
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
                                                   class="form-control">
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
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-role" class=" form-control-label">Role</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <select class="form-control" id="input-role" name="role">
                                                <option value="">
                                                    Please select role
                                                </option>
                                                @foreach($rules as $rule)
                                                    <option value="{{ $rule->id }}">
                                                        {{ $rule->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="text-danger">
                                        Please check your email as the password is delivered via email.
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
            $("#create-user-form").validate({
                errorClass: "is-invalid",
                validClass: "is-valid",
                focusInvalid: true,
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    name: "required",
                    surname: "required",
                    phone: {
                        minlength: 10,
                        maxlength: 10
                    },
                    role: "required"
                },
                messages: {
                    email: {
                        required: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                        email: "รูปแบบอีเมลล์ไม่ถูกต้อง กรุณาตรวจสอบ"
                    },
                    name: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                    surname: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                    phone: {
                        minlength: "เบอร์มือถือต้องมีสิบหลักเท่านั้น",
                        maxlength: "เบอร์มือถือต้องมีสิบหลักเท่านั้น"
                    },
                    role: "ไม่สามารถเป็นค่าว่างได้ กรุณาตรวจสอบ",
                },
                submitHandler: function(form) {
                    var loading = new Loading();
                    form.submit();
                }
            });
        });
    </script>
@endsection
