<!-- dynamic-form.blade.php -->

<div>
    <form>
        @foreach($dynamicData as $key => $data)
            <div>
                @if(is_array($data))
                    <h1>asd</h1>
                @endif

                    @foreach($data as $subkey => $subdata)
                        <input type="text" wire:model="dynamicData.{{ $key }}.{{ $subkey }}" />
                    @endforeach
                <button wire:click.prevent="removeInput({{ $key }})">Remove</button>
            </div>
        @endforeach
        <button wire:click.prevent="addInput">Add 2 Input</button>
        <button wire:click.prevent="addTwoInputs">Add 3 Inputs</button>
        <button wire:click.prevent="printData">Print Data</button>
    </form>
</div>
