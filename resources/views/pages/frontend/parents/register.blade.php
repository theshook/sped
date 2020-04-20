@extends('layouts.app-client-blank')

@section('title')
<title>UCLA::Parent - Register</title>
@endsection

@section('content')
<div class="row full-height form-row-container">
  <div class="col-sm-12 col-lg-5 left-pane">
    <div class="d-flex align-items-baseline">
      <h4 class="font-weight-bolder text-primary">UCLA</h4>

      <a href="/" class="text-sm ml-auto">
        <span class="fa fa-fw fa-long-arrow-alt-left"></span>
        Back to home page
      </a>
    </div>

    <div class="pt-5 mt-3">
      <parent-register host="{{env('API_HOST')}}" />
    </div>
  </div>

  <div class="col-lg-7 right-pane">

  </div>
</div>
@endsection
