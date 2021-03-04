@extends('layouts.app')

@section('content')

<item-page 
    data-movie="{{ $serie }}" 
    data-rate="{{ $serie->rate }}" 
    data-related-movies="{{ $related_series }}" 
    auth="{{ $user }}"
    type="serie"
/>
@endsection