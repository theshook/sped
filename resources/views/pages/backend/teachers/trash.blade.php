@extends('layouts.app')

@section('title')
	<title>ADMIN - Teachers</title>
@endsection

@section('content')
	<teachers-trash host="{{ env('API_HOST') }}" />
@endsection
