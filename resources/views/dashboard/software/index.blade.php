<x-layouts::dashboard>
    <x-page-header :create="route('dashboard.software.create')" class="mb-3" />
    <livewire:datatables.software />
</x-layouts::dashboard>
