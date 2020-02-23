@extends('layouts.app')

@section('title')
<title>ADMIN - Checklists</title>
@endsection

@section('content')
<checklists-index host="{{ env('API_HOST') }}" />
@endsection