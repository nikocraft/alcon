@extends('auth')

@section('title')
Forgot Password | {{ get_website_setting('website.general.title') }}
@endsection

@section('content')
    <auth action="forgot-password"></auth>
@endsection