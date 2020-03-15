@extends('auth')

@section('title')
Sign Up | {{ get_website_setting('website.general.title') }}
@endsection

@section('content')
<auth action="register" :require-fullname={{ json_encode(get_website_setting('website.members.requireFullname', true)) }}></auth>
@endsection