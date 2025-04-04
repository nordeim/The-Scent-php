@props(['text' => 'Loading...'])

<div {{ $attributes->merge(['class' => 'flex items-center justify-center p-4']) }}
     role="status">
    <svg class="animate-spin h-5 w-5 text-sage-600" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
    </svg>
    <span class="ml-3" x-text="text"></span>
</div>
