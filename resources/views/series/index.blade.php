@extends('layouts.app')

@section('content')

<items-page 
    type="Series"
    auth="{{ auth()->user() }}"
/>

@endsection