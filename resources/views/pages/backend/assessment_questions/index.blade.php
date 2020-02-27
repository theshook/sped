@extends('layouts.app')

@section('title')
	<title>ADMIN - Assessment Questions</title>
@endsection

@section('content')
	<teacher-questions-index host="{{ env('API_HOST') }}" userid="{{ Auth::user()->id }}"  />
@endsection