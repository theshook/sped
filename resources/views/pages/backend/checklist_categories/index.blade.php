@extends('layouts.app')

@section('title')
<title>ADMIN - Checklist Category</title>
@endsection

@section('content')
<checklist-categories-index host="{{ env('API_HOST') }}" />
@endsection