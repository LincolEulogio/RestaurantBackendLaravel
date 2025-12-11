@props([
    'header' => null,
    'footer' => null,
])

<div
    {{ $attributes->merge(['class' => 'rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-950 dark:text-gray-50 shadow-sm']) }}>
    @if ($header)
        <div class="flex flex-col space-y-1.5 p-6">
            {{ $header }}
        </div>
    @endif

    <div>
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="flex items-center p-6 pt-0">
            {{ $footer }}
        </div>
    @endif
</div>
