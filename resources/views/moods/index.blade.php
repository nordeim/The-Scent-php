@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-8">Moods</h1>
    <ul>
        @foreach ($moods as $mood)
            <li>{{ $mood->name }} ({{ $mood->products_count }})</li>
        @endforeach
    </ul>
</div>
@endsection
