@props(['checked' => false, 'name' => null])

<div x-data="{
    isChecked: {{ filter_var($checked, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }},
    toggle() { this.isChecked = !this.isChecked; }
}" @click="toggle" :class="isChecked ? 'bg-green-500' : 'bg-gray-200'"
    class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
    role="switch" :aria-checked="isChecked" {{ $attributes }}>

    @if ($name)
        <input type="hidden" name="{{ $name }}" :value="isChecked ? '1' : '0'">
    @endif

    <span class="sr-only">Toggle</span>
    <span aria-hidden="true" :class="isChecked ? 'translate-x-5' : 'translate-x-0'"
        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
    </span>
</div>
