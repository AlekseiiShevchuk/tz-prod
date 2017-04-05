@extends('ap.layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <div class="panel panel-default">

        <div class="panel-body">
            <form id="item_form" action="{{ action('Ap\UsersController@store', $item->id) }}" method="post">
                <input type="hidden" name="manually" value="1" />

                {{ csrf_field() }}

                <div class="well">
                    <h2>Main information</h2>

                    @if(Session::has('flash_message'))
                        <div class="alert {{ Session::has('flash_type') ? 'alert-' . Session::get('flash_type') : 'alert-success' }}">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            {{ Session::get('flash_message') }}
                        </div>
                    @endif

                    @if(count($errors) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            @foreach($errors->getBag('default')->all() as $error)
                                {{$error}}<br>
                            @endforeach
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">

                            @if($item->role == 'parner')

                                <div class="form-group">
                                    <label for="name">Company title</label>
                                    <input maxlength="255" type="text" class="form-control" id="company_title"
                                           name="company_title"
                                           required="required">
                                </div>

                                <div class="form-group">
                                    <label for="name">Surname</label>
                                    <input maxlength="255" type="text" class="form-control" id="surname"
                                           required="required"
                                           name="surname">
                                </div>

                            @endif

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input maxlength="255" type="text" class="form-control" required="required" id="name"
                                       name="name">
                            </div>

                            @if($item->role == 'partner')

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input maxlength="30" type="text" class="form-control" id="password"
                                           required="required"
                                           name="password">
                                </div>
                                <div class="form-group">
                                    <label for="confirm-password">Confirm password</label>
                                    <input maxlength="30" type="text" class="form-control" id="confirm-password"
                                           name="confirm-password" required="required">
                                </div>


                                <div class="form-group">
                                    <label for="name">Surname 2</label>
                                    <input maxlength="255" type="text" class="form-control" id="surname_2"
                                           name="surname_2">
                                </div>

                                <div class="form-group">
                                    <label for="name">Name 2</label>
                                    <input maxlength="255" type="text" class="form-control" id="name_2" name="name_2">
                                </div>


                                <div class="form-group">
                                    <label for="name">Nickame</label>
                                    <input maxlength="255" type="text" class="form-control" id="nickname"
                                           name="nickname">
                                </div>

                                <div class="form-group">
                                    <label for="name">Birthday</label>
                                    <input type="text" class="form-control" datetimepicker="true" id="birthday" name="birthday">
                                </div>

                                <div class="form-group">
                                    <label>Gender</label>

                                    <br/>

                                    <label class="radio-inline">
                                        <input type="radio" name="gender" id="gender_man" value="man"> Man
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" id="gender_woman" value="woman"> Woman
                                    </label>
                                </div>

                            @endif

                            <div class="form-group hidden">
                                <label for="role">Role</label>

                                <select name="role" id="role" class="form-control">

                                    @foreach((new \App\User())->getRoles() as $role)

                                        <option value="{{ $role }}" {{ $role == $item->role ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>

                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" maxlength="255" name="email"
                                       required="required">
                            </div>

                            @if($item->role == 'partner')
                                <div class="form-group">
                                    <label for="email">Email 2</label>
                                    <input type="text" class="form-control" id="email_2" maxlength="255" name="email_2">
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Country</label>
                                            {!! View::make('widgets.InputSelectCountries', ['country_id' => $item->country_id]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name">City</label>
                                    <input type="text" class="form-control" id="city" maxlength="255" name="city">
                                </div>

                                <div class="form-group">
                                    <label for="name">Zip</label>
                                    <input type="text" class="form-control" id="zip" maxlength="255" name="zip">
                                </div>

                                <div class="form-group">
                                    <label for="name">Address 1</label>
                                    <input type="text" class="form-control" id="address1" maxlength="255"
                                           name="address1">
                                </div>

                                <div class="form-group">
                                    <label for="name">Address2</label>
                                    <input type="text" class="form-control" id="birthday" name="address2">
                                </div>

                                <div class="form-group">
                                    <label for="name">Phone country code</label>
                                    <input type="text" class="form-control" id="phone_country_code"
                                           name="phone_country_code"
                                           maxlength="6" onlynumbers="true">
                                </div>

                                <div class="form-group">
                                    <label for="name">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           onlynumbers="true" maxlength="20">
                                </div>

                                <div class="form-group">
                                    <label for="name">Phone country code 2</label>
                                    <input type="text" class="form-control" id="phone_country_code_2"
                                           name="phone_country_code_2">
                                </div>

                                <div class="form-group">
                                    <label for="name">Phone 2</label>
                                    <input type="text" class="form-control" id="phone_2" name="phone_2">
                                </div>


                                @if($item->image)
                                    <div class="form-group">
                                        <img src="{{ $item->getImage() }}" alt="" style="max-width: 200px;"/>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="percent">Percent</label>
                                    <div class="input-group">
                                        <input type="number" max="100" class="form-control" id="percent" name="percent">
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>


                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="is_permanent_subscribe_access"
                                               name="is_permanent_subscribe_access"
                                               @if ($item->is_permanent_subscribe_access) checked @endif> Permanent Access to
                                        non-free
                                        audio
                                    </label>
                                </div>
                            @endif

                                <div class="form-group">
                                    <label for="name">Free access to</label>
                                    <input type="text" class="form-control" id="subscribe_access_to" datetimepicker="true" name="subscribe_access_to">
                                </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary" type="submit" form="item_form">
                Save
            </button>

            <button class="btn btn-link" href="{{ url(action('Ap\UsersController@index')) }}">
                Cencel
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#subscribe_access_to').datetimepicker({
            minDate: new Date(),
            format: '{{ trans('app.date_format') }}'
        });
    });
</script>
@endpush