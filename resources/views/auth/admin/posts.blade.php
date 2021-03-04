@extends('layouts.app')

@section('content')

<admin-page-posts 
    data="{{ $posts }}"
    auth="{{ auth()->user() }}"
/>

@endsection