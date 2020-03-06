@extends('layouts.app')

@section('title')
	<title>ADMIN - Manage Test Questions</title>
@endsection

@section('content')
	<test-manage-questions host="{{ env('API_HOST') }}" />
@endsection