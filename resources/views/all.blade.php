@extends('layouts.app')

@section('content')
    
    <all-page auth="{{ auth()->user() }}" />
    
@endsection