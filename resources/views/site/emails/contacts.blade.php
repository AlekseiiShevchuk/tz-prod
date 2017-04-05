<p>Name Surname: {{ $data['name'] }} {{ $data['surname'] }}</p>
<p>Email: {{ $data['email' ] }}</p>

@if(isset($data['country']))
    <p>Country: {{ $data['country']->name }}</p>
@endif

<p><b>Message: {{ $data['message'] }}</b></p>