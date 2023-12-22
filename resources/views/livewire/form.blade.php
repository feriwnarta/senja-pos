<form wire:submit="save">
    <input type="text" wire:model.live="title">
    <div>
        @error('title') <span text-xs text-red-600>{{ $message }}</span> @enderror
    </div>

    <input type="text" wire:model.live="content">
    <div>
        @error('content') <span text-xs text-red-600>{{ $message }}</span> @enderror
    </div>

    <button type="submit">Save</button>
</form>
