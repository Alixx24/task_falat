@extends('layouts.app') 
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')


    <div class="container">

        <h1>مدیریت یادداشت‌ها</h1>

        @livewire('note-manager') 
    </div>

@endsection
