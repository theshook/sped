@extends('layouts.app')

@section('title')
	<title>ADMIN - Pupils</title>
@endsection

@section('content')
	<pupils-index host="{{ env('API_HOST') }}" />
@endsection