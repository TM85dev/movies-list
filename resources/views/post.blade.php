@extends('layouts.app')

@section('content')

<post-page 
    id="{{ $id }}" 
    auth="{{ auth()->user() }}"
/>

@endsection