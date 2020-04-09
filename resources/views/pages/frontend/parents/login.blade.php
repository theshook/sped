@extends('layouts.app-client')

@section('title')
<title>UCLA::Parent - LOGIN</title>
@endsection

@section('style')
<style>
  body {
    /* background: #010e28 !important; */
    background: url('/images/paint_blue_bg.jpg') !important;
    background-size: cover !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
  }
</style>
@endsection

@section('content')
<parent-login host="{{env('API_HOST')}}" />
@endsection
