@extends('layouts.app')

@section('content')

<incomings-page auth="{{ auth()->user() }}" />

@endsection