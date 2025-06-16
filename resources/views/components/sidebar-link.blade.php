@props([
    'active' => false, 
    'icon' => '', 
    'badge' => '', 
    'badgeColor' => 'bg-blue-500'
])

@php
    $classes = $active 
        ? 'flex items-center px-4 py-3 text-white bg-blue-700 rounded-lg transition-all duration-200 group' 
        : 'flex items-center px-4 py-3 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="{{ $icon }} {{ $active ? 'text-white' : 'text-blue-300 group-hover:text-white' }} w-5"></i>
    @endif
    
    <span class="ml-4 font-medium">{{ $slot }}</span>
    
    @if($badge)
        <span class="ml-auto {{ $badgeColor }} text-white text-xs px-2 py-1 rounded-full">{{ $badge }}</span>
    @else
        <i class="fas fa-chevron-right ml-auto {{ $active ? 'text-white' : 'text-blue-300 group-hover:text-white' }} text-sm"></i>
    @endif
</a>