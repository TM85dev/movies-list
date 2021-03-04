@extends('layouts.app')

@section('content')
    
    <home-page auth="{{ auth()->user() }}" />
    
@endsection