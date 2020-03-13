@extends('layouts.app')

@section('title')
	<title>ADMIN - Provinces</title>
@endsection

@section('content')
	<provinces-trash host="{{ env('API_HOST') }}" />
@endsection