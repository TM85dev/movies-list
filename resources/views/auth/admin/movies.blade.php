@extends('layouts.app')

@section('content')

<admin-page-items 
    data="{{ $movies }}"
    type="movies"
    auth="{{ auth()->user() }}"
/>

@endsection