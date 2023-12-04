<div class="wrapper">

    <div wire:ignore>
        <livewire:components.sidebar.sidebar/>
    </div>

    <div id="content">
        <div id="page">

            {{ $appBar }}


            {{ $slot }}
        </div>
    </div>

</div>
