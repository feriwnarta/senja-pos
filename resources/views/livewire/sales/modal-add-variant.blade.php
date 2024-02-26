<div>
    <div class="modal fade" id="modalAddVariant" tabindex="-1" aria-labelledby="modalAddVariant" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary py-4 justify-content-center">
                    <h1 class="text-white">+ Add Variant</h1>
                </div>
                <div class="modal-body-variant p-0 w-100">
                    <div class="input-group">
                        <input type="text" aria-label="Variant Name"
                            class="form-control border-top-0 border-bottom-0 rounded-0 p-3" placeholder="Variant Name" wire:model='name'>
                        <input type="number" aria-label="Price"
                            class="form-control border-top-0 border-bottom-0 rounded-0 p-3" placeholder="Rp. 0" wire:model='price'>
                        <input type="text" aria-label="SKU"
                            class="form-control border-top-0 border-bottom-0 rounded-0 p-3" placeholder="SKU" wire:model='SKU'>
                    </div>
                </div>
                <div class="modal-footer py-3 gap-2">
                    <div class="btn btn-outline-danger m-0" data-bs-dismiss="modal">
                        Cancel
                    </div>
                    <div class="btn btn-warning m-0" wire:click='move' data-bs-dismiss="modal">
                        Move Selected Items
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
