@extends('layouts.app')

@section('title')
	<title>ADMIN - Teachers</title>
@endsection

@section('content')
	<teachers-index host="{{ env('API_HOST') }}" />
@endsection