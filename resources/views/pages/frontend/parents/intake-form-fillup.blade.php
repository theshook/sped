@extends('layouts.app-client')

@section('content')
<intake-form host="{{env('API_host')}}" />
@endsection
