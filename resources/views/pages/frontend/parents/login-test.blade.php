@extends('layouts.app-client-blank')

@section('title')
<title>UCLA::Parent - Log In</title>
@endsection

@section('style')
<style>
  .main-content {
    padding-top: 0 !important;
  }

  .left-pane {
    background: #fff !important;
    padding: 3rem 7rem !important;
  }

  .right-pane {
    background: url('/images/paint_blue_bg.jpg') !important;
    background-size: cover !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
  }

  @media(max-width: 578px) {
    .left-pane {
      padding: 3rem 2rem !important;
    }

    .right-pane {
      display: none !important;
    }
  }
</style>
@endsection

@section('content')
<div class="row full-height">
  <div class="col-sm-12 col-lg-5 left-pane">
    <h4 class="font-weight-bolder text-primary">UCLA</h4>

    <div class="pt-5 mt-5">
      <parent-login host="{{env('API_HOST')}}" />
    </div>
  </div>

  <div class="col-lg-7 right-pane">

  </div>
</div>
@endsection
