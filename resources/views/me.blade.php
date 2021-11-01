@extends("layouts/master")

@section('head')
    <title>Tesell | Login</title>
@endsection

@section("content")




@include("layouts.header")

<main>

    

<section id="me">

    <h3>Welcome, {{ Auth::user() -> name }}</h3>

    @foreach ($errors->all() as $error)
    <p>{{ $error }}</p>  
    @endforeach

    

    <form method="POST" action="{{ route("updateMe") }}">
    @csrf
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{ Auth::user() -> name }}">



    <label for="email">Email Address</label>
    <input type="text" name="email" id="email" value="{{ Auth::user() -> email }}">

    <input class="btn-primary" type="submit" value="Save Changes">

</form>

</section>

</main>

<script src="{{ asset("/assets/js/alert.js") }}"></script>
@isset($message)
    
@endisset

@endsection