<div class="header-pos">
    <div class="pos-text-wrapper">
        <livewire:components.navbar-kasir.header-kasir>
    </div>
    {{-- <script async>
        var datas = @json($categories);
    </script>
    <div class="pos-category-wrapper" x-data="{
        category: datas,
    }">
        <template x-for="data in category">
            <button type="button" class="button-outline-eded text-medium-12 color-c2c2 p-6-16 h-28" x-text="data">
            </button>
        </template> --}}
    <div class="pos-category-wrapper">
        @foreach ($categories as $category)
            <button type="button" class="button-outline-eded text-medium-12 color-c2c2 p-6-16 h-28">
                {{ $category }}
            </button>
        @endforeach
    </div>
</div>
