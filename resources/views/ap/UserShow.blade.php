@extends('ap.layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <div class="panel panel-default">

        <div class="panel-body">
            <form id="item_form"
                  action="{{ action('Ap\UsersController@update', $item->id) }}"
                  method="post">

                <input type="hidden" name="_method" value="PUT">

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

                    <div class="row">
                        <div class="col-md-12">
                            @if($item->role == 'partner')
                                <div class="form-group">
                                    <label for="name">Affiliate ID</label> {{ $item->aid }}
                                </div>

                                <div class="form-group">
                                    <label for="name">Company title</label>
                                    <input maxlength="255" type="text" class="form-control" id="company_title"
                                           name="company_title"
                                           value="{{ $item->company_title }}">
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="name">Surname</label>
                                <input maxlength="255" type="text" class="form-control" id="surname" name="surname"
                                       value="{{ $item->surname }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input maxlength="255" type="text" class="form-control" id="name" name="name"
                                       value="{{ $item->name }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Password</label>
                                <input maxlength="30" type="text" class="form-control" id="password" name="password"
                                       value="">
                            </div>
                            <div class="form-group">
                                <label for="confirm-password">Confirm password</label>
                                <input maxlength="30" type="text" class="form-control" id="confirm-password"
                                       name="confirm-password"
                                       value="">
                            </div>

                            @if($item->role == 'partner')
                                <div class="form-group">
                                    <label for="name">Surname 2</label>
                                    <input maxlength="255" type="text" class="form-control" id="surname_2"
                                           name="surname_2"
                                           value="{{ $item->surname_2 }}">
                                </div>

                                <div class="form-group">
                                    <label for="name">Name 2</label>
                                    <input maxlength="255" type="text" class="form-control" id="name_2" name="name_2"
                                           value="{{ $item->name_2 }}">
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="name">Nickame</label>
                                <input maxlength="255" type="text" class="form-control" id="nickname" name="nickname"
                                       value="{{ $item->nickname }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Birthday</label>
                                <input type="text" class="form-control" id="birthday" name="birthday"
                                       value="{{ date('d.m.Y',strtotime($item->birthday)) }}">
                            </div>

                            <div class="form-group">
                                <label>Gender</label>

                                <br/>

                                <label class="radio-inline">
                                    <input type="radio" name="gender" id="gender_man"
                                           value="man" {{ $item->gender == 'man' ? 'checked' : '' }}> Man
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" id="gender_woman"
                                           value="woman" {{ $item->gender == 'woman' ? 'checked' : '' }}> Woman
                                </label>
                            </div>


                            <div class="form-group">
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
                                       value="{{ $item->email }}">
                            </div>

                            @if($item->role == 'partner')
                                <div class="form-group">
                                    <label for="email">Email 2</label>
                                    <input type="text" class="form-control" id="email_2" maxlength="255" name="email_2"
                                           value="{{ $item->email_2 }}">
                                </div>
                            @endif

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
                                <input type="text" class="form-control" id="city" maxlength="255" name="city"
                                       value="{{ $item->city }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Zip</label>
                                <input type="text" class="form-control" id="zip" maxlength="255" name="zip"
                                       value="{{ $item->zip }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Address 1</label>
                                <input type="text" class="form-control" id="address1" maxlength="255" name="address1"
                                       value="{{ $item->address1 }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Address2</label>
                                <input type="text" class="form-control" id="birthday" name="address2"
                                       value="{{ $item->address2 }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Phone country code</label>
                                <input type="text" class="form-control" id="phone_country_code"
                                       name="phone_country_code"
                                       maxlength="6" onlynumbers="true"
                                       value="{{ $item->phone_country_code }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                       onlynumbers="true" maxlength="20"
                                       value="{{ $item->phone }}">
                            </div>

                            @if($item->role == 'partner')
                                <div class="form-group">
                                    <label for="name">Phone country code 2</label>
                                    <input type="text" class="form-control" id="phone_country_code_2"
                                           name="phone_country_code_2"
                                           value="{{ $item->phone_country_code_2 }}">
                                </div>

                                <div class="form-group">
                                    <label for="name">Phone 2</label>
                                    <input type="text" class="form-control" id="phone_2" name="phone_2"
                                           value="{{ $item->phone_2 }}">
                                </div>
                            @endif

                            @if($item->image)
                                <div class="form-group">
                                    <img src="{{ $item->getImage() }}" alt="" style="max-width: 200px;"/>
                                </div>
                            @endif

                            @if($item->role == 'partner')
                                <div class="form-group">
                                    <label for="percent">Percent</label>
                                    <div class="input-group">
                                        <input type="number" max="100" class="form-control" id="percent" name="percent"
                                               value="{{ $item->percent }}">
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                            @endif

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="is_permanent_subscribe_access"
                                           name="is_permanent_subscribe_access"
                                           @if ($item->is_permanent_subscribe_access) checked @endif> Access to non-free
                                    audio
                                </label>
                            </div>

                            @if($item->role == 'client')
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"
                                               name="subscribe_news"
                                               value="1"
                                                {{ $item->subscribe_news === 1 ? 'checked' : '' }} /> Subscribe news
                                    </label>
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"
                                               disabled
                                                {{ $item->isSubscriber() ? 'checked' : '' }} /> Payed access
                                    </label>
                                </div>

                                @if($item->isSubscriber())
                                    <div class="form-group">
                                        <label for="name">Payed access to</label>
                                        <input type="text" class="form-control" id="subscribe_access_to" datetimepicker="true" name="subscribe_access_to" value="{{ $item->subscribe_access_to != '-0001-11-30 00:00:00' ? $item->subscribe_access_to->format('d.m.Y') : '' }}" />
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </form>
            {!! Form::open(['method' => 'DELETE', 'id' => 'delete_item_form', 'action' => ['Ap\UsersController@destroy', $item->id]]) !!}
            {!! Form::close() !!}

            {!! Form::open(['method' => 'PUT', 'id' => 'activated_item_form', 'url' => '/ap/users/activate/' . $item->id, 'style' => 'display: inline']) !!}
            {!! Form::close() !!}
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary" onclick="jQuery('#item_form').submit();">
                Save
            </button>

            @if(!$item->trashed())
                <button class="btn btn-danger" onclick="jQuery('#delete_item_form').submit();">
                    Deactivate
                </button>
            @else
                <button href="" class="btn btn-warning" onclick="jQuery('#activated_item_form').submit();">
                    Activate
                </button>
            @endif

            <a class="btn btn-link" href="{{ url(action('Ap\UsersController@index')) }}">
                Cancel
            </a>
        </div>
    </div>



@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#birthday').datetimepicker({
            format: '{{ trans('app.date_format') }}'
        });
    });
</script>
@endpush
