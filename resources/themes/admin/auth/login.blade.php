@extends('auth')

@section('title')
Sign In | {{ get_website_setting('website.general.title') }}
@endsection

@section('content')
<auth action="login" :allow-registrations={{ json_encode(get_website_setting('website.members.allowRegistrations')) }}></auth>
@endsection