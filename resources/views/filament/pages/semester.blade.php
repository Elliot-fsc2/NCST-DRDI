<x-filament::page>
    <div class="space-y-10">
        {{ $this->table }}

        <x-filament::card>
            <h2 class="text-lg font-semibold mb-4">Current Semester</h2>
            {{ $this->form }}
        </x-filament::card>
    </div>
</x-filament::page>
