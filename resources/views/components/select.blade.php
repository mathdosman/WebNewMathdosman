@props(['name'])
<select name="{{ $name }}" id="" class="border-gray-300 mt-2 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
    {{ $slot }}
</select>
