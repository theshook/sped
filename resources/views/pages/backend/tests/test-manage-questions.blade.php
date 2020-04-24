@extends('layouts.app')

@section('title')
	<title>ADMIN - Manage Test Questions</title>
@endsection

@section('content')
	<test-manage-questions :test_id={{$test_id}} />
@endsection
