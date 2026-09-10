@props(['title' => null, 'maxWidth' => 'max-w-md'])

<x-layouts.auth.simple :title="$title" :maxWidth="$maxWidth">
    {{ $slot }}
</x-layouts.auth.simple>
