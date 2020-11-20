@extends('layouts.master')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.css"
          integrity="sha512-CmjeEOiBCtxpzzfuT2remy8NP++fmHRxR3LnsdQhVXzA3QqRMaJ3heF9zOB+c1lCWSwZkzSOWfTn1CdqgkW3EQ=="
          crossorigin="anonymous"/>
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
                                <i class="fa fa-bullhorn"></i>Create post</h3>
                        </div>

                        <div class="card">
                            <div class="card-body card-block">
                                <form action="{{ route('backend.posts.stroed') }}" method="post" enctype="multipart/form-data" class="form-horizontal"
                                      id="my-awesome-post">
                                    @csrf

                                    <div class="row form-group">
                                        <div class="col-12">
                                            <textarea name="description" id="textarea-input" rows="9"
                                                      placeholder="Content..." class="form-control"
                                                      autofocus></textarea>
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

                                <form method="post" action="{{ route('backend.posts.post') }}"
                                      enctype="multipart/form-data" class="dropzone" id="dropzone">
                                    @csrf
                                    <div class="dz-message" data-dz-message>
                                        <span>วางหรือลากไฟล์ที่นี่เพื่ออัปโหลด</span>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary btn-sm" id="location"
                                        onclick="check_in();">
                                    <i class="fa fa-location-arrow"></i> Location
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm" id="upload-file-post">
                                    <i class="fa fa-dot-circle-o"></i> Post
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

    <script type="text/javascript">
        Dropzone.options.dropzone =
            {
                maxFilesize: 10,
                maxFiles: 10,
                renameFile: function (file) {
                    var dt = new Date();
                    var d = dt.getDate();
                    var time = dt.getTime();
                    return d + time + file.name;
                },
                acceptedFiles: 'image/png,image/jpeg,image/gif,image/bmp,video/mov,video/mp4,application/pdf',
                addRemoveLinks: true,
                timeout: 5000,
                removedfile: function (file) {
                    var name = file.upload.filename;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('backend.posts.file.deleted') }}",
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
    </script>
@endsection

@section('script_ready')
    <script type="text/javascript">
        $(function (){
            $("#upload-file-post").on('click', function (){
                new Loading();
                $("#my-awesome-post").submit();
            });
        })
    </script>
@endsection
