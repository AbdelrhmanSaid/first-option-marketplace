<x-layouts::dashboard>
    <x-page-header :create="route('dashboard.disciplines.create')" class="mb-3" />
    <livewire:datatables.disciplines />
</x-layouts::dashboard>
