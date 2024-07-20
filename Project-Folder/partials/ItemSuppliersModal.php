<div class="modal fade" id="ItemSuppliers" tabindex="-1" aria-labelledby="ItemSuppliersView" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header mx-2 d-flex justify-content-between">
                <h5 class="modal-title" id="ItemSuppliersViewLabel">Suppliers for <span id="itemName"></span></h5>
                <div class="d-flex align-items-center">
                    <a href="ItemCostAddForm.php" class="btn btn-primary mx-2">
                        Add Supplier
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover table-striped border">
                        <thead class="bg-white">
                            <tr class="userAdd sticky-top">
                                <th>Supplier Name</th>
                                <th>Supplier Item Cost</th>
                                <th>Supplier Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="supplierTableBody">
                            <!-- Supplier data will be inserted here dynamically -->
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mb-0" id="supplierCount"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemSuppliersModal = document.getElementById('ItemSuppliers');
        if (itemSuppliersModal) {
            itemSuppliersModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const itemId = button.getAttribute('data-item-id');
                const itemName = button.closest('tr').querySelector('td:nth-child(1)').textContent;

                // Update the Add Supplier button link
                const addSupplierButton = document.querySelector('#ItemSuppliers .btn-primary');
                addSupplierButton.href = `ItemCostAddForm.php?itemID=${itemId}&itemName=${encodeURIComponent(itemName)}`;
            });
        }
    });
</script>