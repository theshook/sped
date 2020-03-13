@extends('layouts.app')

@section('title')
	<title>ADMIN - Schools</title>
@endsection

@section('content')
	<schools-trash host="{{ env('API_HOST') }}" />
@endsection
