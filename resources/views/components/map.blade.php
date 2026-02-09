@props([
    'latitude' => null,
    'longitude' => null,
    'address' => null,
    'zoom' => 15,
    'mapType' => 'roadmap',
    'height' => '400px',
    'apiKey' => config('services.google.maps_api_key'),
])

@php
    $heightStyle = is_numeric($height) ? $height . 'px' : $height;
@endphp

<div {{ $attributes->merge(['class' => 'w-full rounded-lg overflow-hidden shadow-lg']) }} style="--map-height: {{ $heightStyle }};">
    @if($apiKey && (($latitude && $longitude) || $address))
        <iframe
            class="w-full border-0"
            style="height: var(--map-height);"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            src="{{ $latitude && $longitude 
                ? 'https://www.google.com/maps/embed/v1/view?key=' . $apiKey . '&center=' . $latitude . ',' . $longitude . '&zoom=' . $zoom . '&maptype=' . $mapType
                : 'https://www.google.com/maps/embed/v1/place?key=' . $apiKey . '&q=' . urlencode($address) . '&zoom=' . $zoom . '&maptype=' . $mapType }}">
        </iframe>
    @else
        <div class="w-full bg-gray-100 flex items-center justify-center text-gray-500" style="height: var(--map-height);">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-sm">Map not available</p>
                @if(!$apiKey)
                    <p class="text-xs mt-1">Google Maps API key not configured</p>
                @endif
            </div>
        </div>
    @endif
</div>
