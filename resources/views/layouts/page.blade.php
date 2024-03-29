<div class="wrapper">

    <div wire:ignore>
        <livewire:components.sidebar.sidebar/>
    </div>


    <div id="content">
        <x-notify::notify/>
        <div id="page">
            @include('loading-fullpage')

            {{ $appBar }}


            {{ $slot }}

        </div>
    </div>

</div>
