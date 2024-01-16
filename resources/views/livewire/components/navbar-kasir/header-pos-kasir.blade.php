<div class="header-pos">
    <div class="pos-text-wrapper">
        <livewire:components.navbar-kasir.header-kasir>
    </div>
    <div class="pos-category-wrapper">
        @foreach ($buttons_category as $category)
        <button type="button" class="pos-category-button"> {{$category}} </button>
        @endforeach
    </div>
</div>