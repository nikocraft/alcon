@extends('auth')

@section('title')
Activate your account at {{ get_website_setting('website.general.title') }}
@endsection

@section('content')
    <auth action="activate" :activated={{ json_encode($activated) }} :auto-approve={{ json_encode($autoApprove) }} :signature-valid={{ json_encode($signatureValid) }}></auth>
@endsection
