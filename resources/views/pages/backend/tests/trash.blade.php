@extends('layouts.app')

@section('title')
	<title>ADMIN - Tests</title>
@endsection

@section('content')
	<tests-trash host="{{ env('API_HOST') }}" />
@endsection
