@extends('layouts.app')

@section('content')

<admin-page-items 
    data="{{ $series }}"
    type="series"
    auth="{{ auth()->user() }}"
/>

@endsection