@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-8">Scent Profiles</h1>
    <ul>
        @foreach ($profiles as $profile)
            <li>{{ $profile->name }} ({{ $profile->products_count }})</li>
        @endforeach
    </ul>
</div>
@endsection
