

<div class="font-interface">
  <form wire:submit="create" class="space-y-6">
    {{ $this->form }}

    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
      <button type="submit"
        class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
        <span wire:loading.remove wire:target="create">Create Group</span>
        <span wire:loading wire:target="create">Creating...</span>
      </button>
    </div>
  </form>

  <x-filament-actions::modals />
</div>
