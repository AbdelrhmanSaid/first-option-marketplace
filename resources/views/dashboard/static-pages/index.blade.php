<x-layouts::dashboard>
    <x-page-header :create="route('dashboard.static-pages.create')" class="mb-3" />
    <livewire:datatables.static-pages />
</x-layouts::dashboard>
