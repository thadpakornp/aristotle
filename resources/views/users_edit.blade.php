@extends('layouts.master')

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ $user->id }}" name="id">
                            <div class="card">
                                <div class="card-header">
                                    Showing data:
                                    <strong>{{ $user->name }} {{ $user->surname }}</strong>

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
                                                   class="form-control" value="{{ $user->name }}" disabled>
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
                                                   class="form-control" value="{{ $user->surname }}" disabled>
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
                                                   class="form-control" value="{{ $user->phone }}" disabled>
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-profile" class=" form-control-label">Profile</label>
                                        </div>
                                        <div class="col col-sm-6">
                                        @if($user->profile != null)
                                            <div class="image">
                                                <img
                                                    src="{{ asset($user->profile) }}"
                                                    alt="{{ $user->name }} {{ $user->surname }}"/>
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-role" class=" form-control-label">Role</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <select class="form-control" id="input-role" name="role" disabled>
                                                @foreach($rules as $rule)
                                                    <option value="{{ $rule->slug }}" @if($user->hasRole($rule->slug) == $rule->slug) selected @endif>
                                                        {{ $rule->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr/>


                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-status" class=" form-control-label">Status</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            <label class="switch switch-3d switch-success mr-3">
                                                <input type="checkbox" id="input-status" class="switch-input" checked="@if($user->deleted_at == null) true @else false @endif" disabled>
                                                <span class="switch-label"></span>
                                                <span class="switch-handle"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-register" class=" form-control-label">Register at</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            {{ $user->created_at }}
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-update" class=" form-control-label">Update at</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            {{ $user->updated_at }}
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="row form-group">
                                        <div class="col col-sm-5">
                                            <label for="input-verify" class=" form-control-label">Verify at</label>
                                        </div>
                                        <div class="col col-sm-6">
                                            {{ $user->email_verified_at }}
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <a class="btn btn-warning btn-sm" id="edit" onclick="edit();" href="#">
                                        <i class="fa fa-dot-circle-o"></i> Edit
                                    </a>
                                    <button type="submit" class="btn btn-success btn-sm" id="submit" style="display: none;"><i class="fa fa-dot-circle-o"></i> Save</button>
                                    <a class="btn btn-danger btn-sm" href="#" onclick="deleted();" id="delete">
                                        <i class="fa fa-ban"></i> Delete
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="#" onclick="canceled();" id="cancel" style="display: none;">
                                        <i class="fa fa-ban"></i> Cancel
                                    </a>
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
    <script type="text/javascript">
        function deleted() {
            Swal.fire({
                title: 'Are you sure?',
                text: "คุณต้องการปิดการใช้งานใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    var loading = new Loading();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('backend.users.deleted') }}",
                        data: {id: {{ $user->id }}, "_token": "{{ csrf_token() }}"},
                        success: function (msg){
                            loading.out();
                            Swal.fire(
                                'Deleted!',
                                '',
                                'success'
                            ).then(function () {
                                window.location.assign('{{ route('backend.users.index') }}');
                            });
                        },
                        error: function (e)
                        {
                            loading.out();
                            Swal.fire(
                                'Deleted!',
                                e.responseText,
                                'error'
                            )
                        }
                    });
                }
            });
        }

        function loadingOut(loading) {
            loading.out();
        }

        function edit() {
            $('#edit').hide();
            $('#delete').hide();
            $('#submit').show();
            $('#cancel').show();
            $('#input-status').prop("disabled", false);
            $('#input-role').prop("disabled", false);
        }

        function canceled()
        {
            $('#edit').show();
            $('#delete').show();
            $('#submit').hide();
            $('#cancel').hide();
            $('#input-status').prop("disabled", true);
            $('#input-role').prop("disabled", true);
        }
    </script>
@endsection
