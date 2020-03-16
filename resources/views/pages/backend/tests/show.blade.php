@extends('layouts.app')

@section('title')
	<title>ADMIN - View Test</title>
@endsection

@section('content')
	<test-show host="{{ env('API_HOST') }}" test_id="{{$test_id}}" />
@endsection
