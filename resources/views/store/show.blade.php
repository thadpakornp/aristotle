@extends('layouts.master')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.css"
          integrity="sha512-CmjeEOiBCtxpzzfuT2remy8NP++fmHRxR3LnsdQhVXzA3QqRMaJ3heF9zOB+c1lCWSwZkzSOWfTn1CdqgkW3EQ=="
          crossorigin="anonymous"/>

    <style type="text/css">
        .error {
            color: red;
        }
    </style>
@endsection

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="row">
                <div class="col-lg-12">
                    <div class="custom-tab">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab"
                                   href="#nav-profile" role="tab" aria-controls="nav-profile"
                                   aria-selected="false"><strong>Courses List</strong></a>
                                <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home"
                                   role="tab" aria-controls="nav-home"
                                   aria-selected="true"><strong>Edit Channel</strong></a>
                                @if(auth()->user()->hasPermission('delete.store'))<a
                                    class="nav-item nav-link text-danger" href="#"
                                    onclick="deletechannel({{ $store->id }});"><strong>Delete
                                        Channel</strong></a>@endif

                                @if(auth()->user()->hasRole('admin'))
                                    @if($store->status == '0')
                                        <a href="#" class="btn btn-primary"
                                           onclick="approve({{ $store->id }});"><strong>อนุมัติ</strong></a>
                                        <a href="#" class="btn btn-danger"
                                           onclick="noapprove({{ $store->id }});"><strong>ไม่อนุมัติ</strong></a>
                                    @endif
                                @endif
                            </div>
                        </nav>


                        <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-profile" role="tabpanel"
                                 aria-labelledby="nav-profile-tab">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="overview-wrap">
                                                <h2 class="title-1">Courses List</h2>
                                                @if(auth()->user()->hasPermission('create.course'))
                                                    <a class="au-btn au-btn-icon au-btn--blue"
                                                       href="{{ route('backend.store.course.create',$store->id) }}">
                                                        <i class="zmdi zmdi-plus"></i>add course
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
                                                        <th>Name(TH)</th>
                                                        <th>Name(EN)</th>
                                                        <th>Professor</th>
                                                        <th>Description</th>
                                                        <th>cost</th>
                                                        <th>Discount cost</th>
                                                        <th>จำนวนบทเรียน</th>
                                                        <th>จำนวนชั่วโมง</th>
                                                        <th>จำนวนที่รับ</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($courses as $course)
                                                        <tr class="clickable-row"
                                                            data-href="{{ route('backend.store.course.edit', $course->id) }}">
                                                            <td>{{ $course->name_th }}</td>
                                                            <td>{{ $course->name_en }}</td>
                                                            <td>{{ $course->professor }}</td>
                                                            <td>{{ $course->description }}</td>
                                                            <td class="text-center">{{ $course->full_cost }}</td>
                                                            <td class="text-center">{{ $course->discount_cost }}</td>
                                                            <td class="text-center">{{ $course->num_course }}</td>
                                                            <td class="text-center">{{ $course->num_hour }}</td>
                                                            <td class="text-center">{{ $course->num }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center"> ไม่พบข้อมูล</td>
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                                    <div class="au-card-title">
                                        <div class="bg-overlay bg-overlay--blue"></div>
                                        <h3>
                                            <i class="fa fa-book"></i>Edit Channel</h3>
                                    </div>
                                    <div class="card">
                                        <div class="card-body card-block">
                                            <form action="{{ route('backend.store.edit') }}" method="post"
                                                  enctype="multipart/form-data"
                                                  class="form-horizontal"
                                                  id="my-awesome-post">
                                                @csrf
                                                <input type="hidden" value="{{ $store->id }}" name="id">
                                                <div class="row form-group">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="name"
                                                               placeholder="Name*"
                                                               autocomplete="off" required value="{{ $store->name }}">
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-12">
                                            <textarea name="description" id="textarea-input" rows="9"
                                                      placeholder="Description*" class="form-control"
                                                      required>{{ $store->description }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="address"
                                                               placeholder="Address*" autocomplete="off" required
                                                               value="{{ $store->address }}">
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-3">
                                                        <select class="form-control" name="province" id="mega-province"
                                                                onchange="getAddress(this.name, 'country', 'a')"
                                                                required>
                                                            <option value="">โปรดเลือดจังหวัด*</option>
                                                            @foreach($provinces as $province)
                                                                <option
                                                                    value="{{ $province->PROVINCE_ID }},{{ $province->PROVINCE_NAME }}"
                                                                    @if($province->PROVINCE_NAME == $store->province) selected @endif>{{ $province->PROVINCE_NAME }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <select class="form-control" name="country" id="mega-country"
                                                                onchange="getAddress(this.name, 'district', 't')"
                                                                required>
                                                            <option value="">โปรดเลือดอำเภอ/เขต*</option>
                                                            @foreach($amphurs as $amphur)
                                                                <option
                                                                    value="{{ $amphur->AMPHUR_ID }},{{ $amphur->AMPHUR_NAME }}"
                                                                    @if($amphur->AMPHUR_NAME == $store->amphur) selected @endif>{{ $amphur->AMPHUR_NAME }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <select class="form-control" name="district" id="mega-district"
                                                                onchange="getAddress(this.name, 'code', 'z')" required>
                                                            <option value="">โปรดเลือดตำบล/แขวง*</option>
                                                            @foreach($districts as $district)
                                                                <option
                                                                    value="{{ $district->DISTRICT_CODE }},{{ $district->DISTRICT_NAME }}"
                                                                    @if($district->DISTRICT_NAME == $store->district) selected @endif>{{ $district->DISTRICT_NAME }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <input class="form-control" type="text" id="mega-code"
                                                               name="code"
                                                               placeholder="Zipcode*" required
                                                               value="{{ $store->zipcode }}">
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-4">
                                                        <input type="email" class="form-control" name="email"
                                                               placeholder="Email*"
                                                               autocomplete="off" required value="{{ $store->email }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" class="form-control" name="phone"
                                                               placeholder="Phone*"
                                                               autocomplete="off" required value="{{ $store->phone }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="text" class="form-control" name="line"
                                                               placeholder="Line ID"
                                                               autocomplete="off" value="{{ $store->line }}">
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-12">
                                                        <div id="check-in"
                                                             style="@if($store->g_lat == null && $store->g_lng == null) display: none; @else display: block; @endif">
                                                            <br/>
                                                            <input type="text" id="address-input" name="address_address"
                                                                   class="form-control map-input">
                                                            <input type="hidden" name="g_location_lat"
                                                                   id="address-latitude"
                                                                   value="@if($store->g_lat == null) 0 @else {{ $store->g_lat }} @endif"/>
                                                            <input type="hidden" name="g_location_long"
                                                                   id="address-longitude"
                                                                   value="@if($store->g_lng == null) 0 @else {{ $store->g_lng }} @endif"/>
                                                            <div id="address-map-container"
                                                                 style="width:100%;height:400px;">
                                                                <div style="width: 100%; height: 100%"
                                                                     id="address-map"></div>
                                                            </div>
                                                            <input id="latformHRML"
                                                                   value="@if($store->g_lat == null) 13.744674 @else {{ $store->g_lat }} @endif"
                                                                   type="hidden">
                                                            <input id="longformHRML"
                                                                   value="@if($store->g_lng == null) 100.5633683 @else {{ $store->g_lng }} @endif"
                                                                   type="hidden">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            @if($store_files->count() < 3)
                                                <form method="post" action="{{ route('backend.post') }}"
                                                      enctype="multipart/form-data" class="dropzone" id="dropzone">
                                                    @csrf
                                                    <div class="dz-message" data-dz-message>
                                                        <span>วางหรือลากรูปเพื่ออัปโหลด(สูงสุด {{ 3 - $store_files->count() }} รูป)</span>
                                                    </div>
                                                </form>
                                            @endif

                                            @if($store_files->count() > 0)
                                                <div class="row pt-2">
                                                    @foreach($store_files->get() as $file)
                                                        <div class="col-4" id="file-{{ $file->id }}"><img
                                                                src="{{ asset('media/'.$file->name) }}"
                                                                class="img-fluid" alt="Responsive image"
                                                                max-height="200px">
                                                            <p class="text-center text-danger"
                                                               style="pointer-events: auto;"
                                                               onclick="deleteImage('{{ $file->name }}',{{ $file->id }})">
                                                                <i class="fa fa-remove text-danger"></i> Delete</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="card-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" id="location"
                                                    onclick="check_in();">
                                                <i class="fa fa-location-arrow"></i> Location
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-sm" id="upload-file-post">
                                                <i class="fa fa-dot-circle-o"></i> Submit
                                            </button>
                                            <a type="button" class="btn btn-danger btn-sm pull-right text-white"
                                               href="{{ route('backend.store.index') }}">
                                                <i class="fa fa-backward"></i> Back
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.js"
            integrity="sha512-0QMJSMYaer2wnpi+qbJOy4rOAlE6CbYImSlrgQuf2MBBMqTvK/k6ZJV126/EbdKzMAXaB6PHzdYxOX6Qey7WWw=="
            crossorigin="anonymous"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initialize"
        async defer></script>
    <script src="{{ asset('js/mapInput.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"
            integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg=="
            crossorigin="anonymous"></script>
    <script type="text/javascript">
        Dropzone.options.dropzone =
            {
                maxFilesize: 10,
                maxFiles: 3 - {{ $store_files->count() }},
                renameFile: function (file) {
                    var dt = new Date();
                    var d = dt.getDate();
                    var time = dt.getTime();
                    return d + time + file.name;
                },
                acceptedFiles: 'image/png,image/jpeg',
                addRemoveLinks: true,
                timeout: 5000,
                removedfile: function (file) {
                    var name = file.upload.filename;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('backend.file.deleted') }}",
                        data: {"_token": "{{ csrf_token() }}", filename: name},
                        success: function (data) {
                            //console.log(data);
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                    var fileRef;
                    return (fileRef = file.previewElement) != null ?
                        // fileRef.parentNode.removeChild(file.previewElement) : void 0;
                        fileRef.style.display = "none" : void 0;
                },
                success: function (file, response) {
                    //console.log(response.html);
                },
                error: function (file, response) {
                    Swal.fire('Error!', response, 'error');
                    this.removeFile(file)
                }
            };

        function deletechannel(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "ต้องการลบชาแนลนี้? เมื่อลบแล้วรายชื่อคอร์สทั้งหมดจะถูกลบทั้งหมด",
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
                        url: "{{ route('backend.store.deleted') }}",
                        data: {id: id, "_token": "{{ csrf_token() }}"},
                        success: function (msg) {
                            window.location.assign('{{ route("backend.store.index") }}');
                        },
                        error: function (e) {
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

        function deleteImage(name, id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "การลบรูปจะมีผลทันที โดยไม่ต้องกดปุ่มเซฟ ดำเนินการต่อหรือไม่?",
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
                        url: "{{ route('backend.store.file.deleted') }}",
                        data: {name: name, "_token": "{{ csrf_token() }}"},
                        success: function (msg) {
                            loading.out();
                            Swal.fire(
                                'Deleted!',
                                '',
                                'success'
                            ).then(function () {
                                $('#file-' + id).hide();
                            });
                        },
                        error: function (e) {
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

        function check_in() {
            if ($('#check-in').css('display') == 'none') {
                $('#check-in').show();
            } else {
                $('#check-in').hide();
            }
        }

        function getAddress(iSelect, toSelect, iMode) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
                {
                    type: "POST",
                    url: "{{ route('backend.getAddress') }}",
                    data: {find: iMode, fvalue: $('select[name=' + iSelect + ']').val()},
                    success: function (data) {
                        if (iMode == "z") {
                            $('input[name=' + toSelect + ']').val(data);
                        } else {
                            $('select[name=' + toSelect + ']').empty().append(data);
                            $('input[name=code]').val('');
                        }

                        if (iMode == "a") {
                            var sname = "select[name=SubDistrict]";
                            $(sname).empty().append("<option value=\"\" selected=\"selected\">:::::&nbsp;เลือก&nbsp;:::::</option>");
                        }
                    }, error: function (e) {
                        console.log(e)
                    }
                });
        }

        function approve(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "ยืนยันการอนุมัติ?",
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
                        url: "{{ route('backend.store.approve') }}",
                        data: {id: id, "_token": "{{ csrf_token() }}"},
                        success: function (msg) {
                            window.location.assign(msg);
                        },
                        error: function (e) {
                            console.log(e);
                            loading.out();
                            Swal.fire(
                                'Error!',
                                e.responseText,
                                'error'
                            )
                        }
                    });
                }
            });
        }

        function noapprove(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "ยืนยันไม่อนุมัติ?",
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
                        url: "{{ route('backend.store.noapprove') }}",
                        data: {id: id, "_token": "{{ csrf_token() }}"},
                        success: function (msg) {
                            window.location.assign(msg);
                        },
                        error: function (e) {
                            console.log(e);
                            loading.out();
                            Swal.fire(
                                'Error!',
                                e.responseText,
                                'error'
                            )
                        }
                    });
                }
            });
        }
    </script>
@endsection

@section('script_ready')
    <script type="text/javascript">
        $(function () {
            $("#upload-file-post").on('click', function () {
                // $("#my-awesome-post").valid();
                if ($("#my-awesome-post").valid()) {
                    new Loading();
                    $("#my-awesome-post").submit();
                }
            });

            $(".clickable-row").click(function () {
                new Loading();
                window.location = $(this).data("href");
            });
        })
    </script>
@endsection
