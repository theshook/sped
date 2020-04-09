@extends('layouts.app-client')

@section('title')
<title>UCLA::Pupil - Assessment Tests List</title>
@endsection

@section('content')
<pupil-assessments-index host="{{ env('API_HOST')}}" />
@endsection

@section('scripts')
<script>
  $('.link-to-assessments').addClass('active');
</script>
@endsection
