@extends('layouts.app-client')

@section('title')
<title>UCLA::Pupil - Tests List</title>
@endsection

@section('content')
<h1 class="font-weight-bolder text-custom-dark mb-4">Assessment Tests</h1>

<pupil-assessments-index host="{{ env('API_HOST')}}" />
@endsection

@section('scripts')
<script>
  $('.link-to-assessments').addClass('active');
</script>
@endsection
