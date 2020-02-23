@extends('layouts.app')

@section('title')
	<title>ADMIN - Reports</title>
@endsection

@section('content')
	<reports-index host="{{ env('API_HOST') }}" />
@endsection