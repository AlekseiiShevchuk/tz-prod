@if(count($buttons))
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                @foreach($buttons as $button)
                    <a href="{{ $button['action'] }}"
                       class="btn btn-default navbar-btn {{ isset($button['class']) ? $button['class'] : '' }}"
                       title="{{ $button['title'] }}">{!! $button['anchor'] !!}</a>
                @endforeach

                @if(isset($search) && $search)
                    <form class="navbar-form navbar-right" role="search" method="get">
                        <div class="form-group">
                            <input type="text" name="query" class="form-control" @if(isset($query))value="{{$query}}" @endif placeholder="Search">
                            @if(isset($role)) <input type="hidden" name="role" class="form-control" value="{{$role}}" placeholder="Search"> @endif
                            @if(isset($current_per_page)) <input type="hidden" name="per_page" class="form-control" value="{{$current_per_page}}" placeholder="Search"> @endif
                        </div>
                        <button type="submit" class="btn btn-default">Search</button>
                    </form>
                @endif
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
@endif