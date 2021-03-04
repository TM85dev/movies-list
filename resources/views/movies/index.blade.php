@extends('layouts.app')

@section('content')

<items-page 
    type="Movies"  
    auth="{{ auth()->user() }}"
/>

@endsection