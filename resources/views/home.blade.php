@extends('layouts.master')

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="row">
                <div class="col-lg-12">
                    <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                        <div class="au-card-title">
                            <div class="bg-overlay bg-overlay--blue"></div>
                            <h3>
                                <i class="fa fa-bullhorn"></i>Feeds</h3>
                            <a class="au-btn-plus" href="{{ route('backend.posts.create') }}">
                                <i class="zmdi zmdi-plus"></i>
                            </a>
                        </div>

                        <div class="au-message">
                            <div class="au-message-list" id="load_data">
                            </div>
                        </div>
                        <div id="load_data_message"></div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
@endsection

@section('script_ready')
    <script type="text/javascript">
        $(function (){
            var limit = 4;
            var start = 0;
            var action = 'inactive';

            if(action == 'inactive')
            {
                action = 'active';
                load_data(limit, start);
            }
            $(window).scroll(function(){
                if($(window).scrollTop() + $(window).height() > $("#load_data").height() && action == 'inactive')
                {
                    action = 'active';
                    start = start + limit;
                    setTimeout(function(){
                        load_data(limit, start);
                    }, 1000);
                }
            });

            function load_data(limit, start)
            {
                $.ajax({
                    url:"{{ route('backend.posts.index') }}",
                    method:"POST",
                    data:{limit:limit, start:start,"_token": "{{ csrf_token() }}"},
                    cache:false,
                    success:function(data)
                    {
                        // console.log(data);
                        $('#load_data').append(data);
                        if(data == '')
                        {
                            $('#load_data_message').html("<div class='text-center'>No Data Found</div>");
                            action = 'active';
                        }
                        else
                        {
                            $('#load_data_message').html("<div class='text-center'><img src='{{ asset('images/loading.gif') }}' width='24px' height='24px'></div>");
                            action = "inactive";
                        }
                    }
                });
            }
        });
    </script>
@endsection
