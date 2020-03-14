@extends('layouts.app')

@section('title')
	<title>ADMIN - Pupils</title>
@endsection

@section('content')
	<pupils-trash host="{{ env('API_HOST') }}" />
@endsection
