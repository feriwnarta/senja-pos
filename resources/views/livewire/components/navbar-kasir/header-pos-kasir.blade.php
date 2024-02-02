<div class="header-pos">
    <div class="pos-text-wrapper">
        <livewire:components.navbar-kasir.header-kasir>
    </div>
    <div class="pos-category-wrapper">
        @foreach ($categories as $category)
            <button type="button" class="button-outline-eded text-medium-12 color-c2c2 p-6-16 h-28">
                {{ $category }}
            </button>
        @endforeach
    </div>
</div>
