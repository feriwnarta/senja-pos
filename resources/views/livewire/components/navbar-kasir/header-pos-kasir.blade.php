<div class="header-pos">
    <div class="pos-text-wrapper">
        <h1 class="pos-text">Point of Sales</h1>
    </div>
    <div class="pos-category-wrapper">
        @foreach ($buttons_category as $category)
        <button type="button" class="pos-category-button"> {{$category}} </button>
        @endforeach
    </div>
</div>