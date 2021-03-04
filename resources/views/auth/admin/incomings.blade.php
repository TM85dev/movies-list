@extends('layouts.app')

@section('content')

<admin-page-incomings 
    data="{{ $incomings }}"
    auth="{{ auth()->user() }}"
/>

@endsection