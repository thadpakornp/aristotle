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
                                <i class="fa fa-book"></i>Create Course</h3>
                        </div>

                        <div class="card">
                            <div class="card-body card-block">
                                <form action="{{ route('backend.store.course.store') }}" method="post" enctype="multipart/form-data"
                                      class="form-horizontal"
                                      id="my-awesome-post">
                                    @csrf
                                    <input type="hidden" name="stores_id" value="{{ $id }}">
                                    <div class="row form-group">
                                        <div class="col-12">
                                            <input type="text" class="form-control" name="name_th" placeholder="Name (TH)*"
                                                   autocomplete="off" autofocus required>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <input type="text" class="form-control" name="name_en" placeholder="Name (EN)*"
                                                   autocomplete="off" autofocus required>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <input type="text" class="form-control" name="professor" placeholder="Professor*"
                                                   autocomplete="off" autofocus required>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <textarea name="description" id="textarea-input" rows="9"
                                                      placeholder="Description*" class="form-control"
                                                      required maxlength="250"></textarea>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <label>Cover</label>
                                            <input type="file" class="form-control" name="cover">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-4">
                                            <select class="form-control" name="type_course" required>
                                                <option value="">โปรดเลือกประเภท*</option>
                                                <option value="live">สอนสด</option>
                                                <option value="live-online">ออนไลน์สด</option>
                                                <option value="online">ออนไลน์</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="full_cost" placeholder="Full cost*"
                                                   autocomplete="off" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="discount_cost" placeholder="Discount cost"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="num_course" placeholder="จำนวนบทเรียน*"
                                                   autocomplete="off" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="num_hour" placeholder="จำนวนชั่วโมง*"
                                                   autocomplete="off" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="num" placeholder="จำนวนที่รับ*"
                                                   autocomplete="off" required>
                                        </div>
                                    </div>

                                </form>

                                <form method="post" action="{{ route('backend.store.course.post') }}"
                                      enctype="multipart/form-data" class="dropzone" id="dropzone">
                                    @csrf
                                    <div class="dz-message" data-dz-message>
                                        <span>วางหรือลากรูปเพื่ออัปโหลด(สูงสุด 6 รูป)</span>
                                    </div>
                                </form>
                            </div>

                            <div class="card-footer">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"
            integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg=="
            crossorigin="anonymous"></script>
    <script type="text/javascript">
        Dropzone.options.dropzone =
            {
                maxFilesize: 10,
                maxFiles: 6,
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
                        url: "{{ route('backend.store.course.file.deleted') }}",
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
