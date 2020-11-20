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
                    <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                        <div class="au-card-title">
                            <div class="bg-overlay bg-overlay--blue"></div>
                            <h3>
                                <i class="fa fa-book"></i>Create Channel</h3>
                        </div>

                        <div class="card">
                            <div class="card-body card-block">
                                <form action="{{ route('backend.stroed') }}" method="post" enctype="multipart/form-data"
                                      class="form-horizontal"
                                      id="my-awesome-post">
                                    @csrf

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <input type="text" class="form-control" name="name" placeholder="Name*"
                                                   autocomplete="off" autofocus required>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <textarea name="description" id="textarea-input" rows="9"
                                                      placeholder="Description*" class="form-control"
                                                      required></textarea>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <input type="text" class="form-control" name="address"
                                                   placeholder="Address*" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-3">
                                            <select class="form-control" name="province" id="mega-province"
                                                    onchange="getAddress(this.name, 'country', 'a')" required>
                                                <option value="">โปรดเลือดจังหวัด*</option>
                                                @foreach($provinces as $province)
                                                    <option
                                                        value="{{ $province->PROVINCE_ID }},{{ $province->PROVINCE_NAME }}">{{ $province->PROVINCE_NAME }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <select class="form-control" name="country" id="mega-country"
                                                    onchange="getAddress(this.name, 'district', 't')" required>
                                                <option value="">โปรดเลือดอำเภอ/เขต*</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <select class="form-control" name="district" id="mega-district"
                                                    onchange="getAddress(this.name, 'code', 'z')" required>
                                                <option value="">โปรดเลือดตำบล/แขวง*</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control" type="text" id="mega-code" name="code"
                                                   placeholder="Zipcode*" required>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-4">
                                            <input type="email" class="form-control" name="email" placeholder="Email*"
                                                   autocomplete="off" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="phone" placeholder="Phone*"
                                                   autocomplete="off" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="line" placeholder="Line ID"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <div id="check-in" style="display: none">
                                                <br/>
                                                <input type="text" id="address-input" name="address_address"
                                                       class="form-control map-input">
                                                <input type="hidden" name="g_location_lat" id="address-latitude"
                                                       value="0"/>
                                                <input type="hidden" name="g_location_long" id="address-longitude"
                                                       value="0"/>
                                                <div id="address-map-container" style="width:100%;height:400px;">
                                                    <div style="width: 100%; height: 100%" id="address-map"></div>
                                                </div>
                                                <input id="latformHRML" value="13.744674" type="hidden">
                                                <input id="longformHRML" value="100.5633683" type="hidden">
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form method="post" action="{{ route('backend.post') }}"
                                      enctype="multipart/form-data" class="dropzone" id="dropzone">
                                    @csrf
                                    <div class="dz-message" data-dz-message>
                                        <span>วางหรือลากรูปเพื่ออัปโหลด(สูงสุด 3 รูป)</span>
                                    </div>
                                </form>
                            </div>

                            @if(!auth()->user()->hasRole('admin'))
                                <div class="text-danger text-center font-weight-bold">&nbsp;**การสร้างชาแนล <u>อาจมีค่าใช้จ่ายเกิดขึ้น</u>
                                    กรุณาตรวจสอบช่องทางติดต่อกลับให้ถูกต้อง เพื่อการพิจารณาอนุมัติเผยแพร่สถาบันของท่าน**
                                </div>
                                <p class="font-weight-bold text-center">&nbsp;ระหว่างการตรวจสอบท่านสามารถดำเนินการแก้ไขข้อมูล
                                    เพิ่มข้อมูลหลักสูตรได้ตามปกติ</p>
                            @endif
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary btn-sm" id="location"
                                        onclick="check_in();">
                                    <i class="fa fa-location-arrow"></i> Location
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm" id="upload-file-post">
                                    <i class="fa fa-dot-circle-o"></i> Submit
                                </button>
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
                maxFiles: 3,
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
        })
    </script>
@endsection
