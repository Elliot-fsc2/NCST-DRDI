@assets
<link href="{{ Vite::asset('resources/css/filament/app.css') }}" rel="stylesheet">
@endassets
<div>
  <form wire:submit="submit">
    {{ $this->form }}

    <x-filament::button type="submit" class="w-full mt-6" size="lg">
      Submit
    </x-filament::button>
  </form>

  <x-filament-actions::modals />
</div>
