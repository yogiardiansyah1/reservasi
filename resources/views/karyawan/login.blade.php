@extends('layout.app')
@section('content')
    <form class="login" method="POST" action="/resto/cek">
        {{ csrf_field() }}
        <input class="inp" type="text" name="id" placeholder="ID" required>
        <input class="inp" type="password" name="password" placeholder="Password" required>
        <input class="btn" type="submit" value="Login">
    </form>
@endsection
