<div>
  <form wire:submit="create">
    {{ $this->form }}

    <x-filament::button type="submit" class="mt-4 w-full bg-sky-600 text-white">
      Submit
    </x-filament::button>
  </form>

  <x-filament-actions::modals />
</div>