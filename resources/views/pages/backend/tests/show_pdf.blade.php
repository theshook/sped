@extends('layouts.app-pdf-container')

@section('title')
	<title>ADMIN - View Test (PDF)</title>
@endsection

@section('content')
	<div class="container">
    {{$test}}
    {{$questions}}
  </div>
@endsection
