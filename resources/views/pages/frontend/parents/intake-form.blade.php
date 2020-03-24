@extends('layouts.app-client')

@section('title')
<title>UCLA::Intake Form</title>
@endsection

@section('content')
<div class="card shadow-sm col-lg-6 mx-auto">
  <div class="card-body py-5">
    <h5 class="text-muted text-center mb-4">
      UCLA Children's Hospital
      Development-Behavioral Pediatrics
    </h5>

    <p class="text-muted mb-2">Dear Caregivers,</p>
    <p class="text-muted text-sm text-justify" style="line-height: 1.7rem !important;">
      This document provides your assessment team at the Developmental-Behavioral Pediatric Program with information to ensure the most productive visit for you and your child. Please complete the Intake Form providing as much information possible and return it to our program coordinator. Rosaura Cueva. via email, fax or mail soon as you can. So we can schedule your visit.
      Additionally, please send us copies of any developmental-behavioral information on your child, as well as asking any daycare providers, teachers, therapists or other relevant providers to fill out the Provider Questionnaire. These forms ideally would be send before the visit if possible. Please bring the originals to the visit.
    </p>

    <div class="text-center mt-4">
      <button type="button" class="btn btn-info shadow">Proceed</button>
    </div>
  </div>
</div>
@endsection
