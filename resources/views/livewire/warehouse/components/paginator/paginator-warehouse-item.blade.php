<div>

    <table class="table-component table table-hover margin-top-16"
    >
        <thead class="sticky-top">
        <tr>

            <th>Item</th>
            <th>Jumlah</th>
            <th>Unit</th>
        </tr>
        </thead>
        <tbody>
        @foreach($warehouseItems as $warehouseItem)
            @if($warehouseItem->items->route == 'BUY')
                <tr wire:key="{{  }}">
                    <td>{{ $warehouseItem->items->name }}</td>
                    <td></td>
                    <td>{{ $warehouseItem->items->unit->name }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

</div>
