@extends('auth')

@section('title')
{{ get_website_setting('website.general.title') }} | Reset Password
@endsection

@section('content')
    <auth action="reset-password" token="{{ $token }}" email="{{ $email }}"></auth>
@endsection