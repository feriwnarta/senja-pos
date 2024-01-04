<x-page-layout-pos>
    <div class="menu-content-wrapper">
        <div class="menu-content d-flex flex-lg-wrap gap-3">
            @for ($i = 0; $i < 30; $i++)
                <Button type="button" class="menu-card">
                    <div class="card-body">
                        <img src="../img/chicken.png" alt="Test">
                    </div>
                    <div class="card-footer">
                        Bubur Hot Pot
                    </div>
                </Button>
            @endfor
        </div>
    </div>
</x-page-layout-pos>