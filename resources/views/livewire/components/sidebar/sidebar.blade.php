<!-- Sidebar  -->
<nav id="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('img/cahaya_senja_logo.png') }}" alt="logo cahaya senja" class="logo-sidebar">
    </div>

    <div class="button-menu">
        <div class="dashboard-btn">

            <div class="accordion" id="accordionMenu">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <a href="/pos/dashboard" wire:navigate>
                            <button class="accordion-button button-icon-text description-1-medium collapsed"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseDashboard"
                                    aria-expanded="false">
                                <i class="pos-icon"></i>
                                Dashboard
                            </button>
                        </a>
                    </h2>
                    <div id="collapseDashboard" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button" id="">
                                Ringkasan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed" type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapsePos">
                            <i class="pos-icon"></i>
                            Point Of Sales
                        </button>
                    </h2>
                    <div id="collapsePos" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <a href="/point-of-sales/menu" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium"
                                        type="button" id="">
                                    Menu
                                </button>
                            </a>
                            <a href="/point-of-sales/category" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    Category
                                </button>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">

                        <button class="accordion-button button-icon-text description-1-medium collapsed"
                                type="button" data-bs-toggle="collapse" aria-expanded="false"
                                data-bs-target="#collapseIngredients">
                            <i class="ingredients-icon"></i>
                            Ingredients
                        </button>

                    </h2>
                    <div id="collapseIngredients" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <a href="ingredients/library" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    Library
                                </button>
                            </a>
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button" id="">
                                Category
                            </button>
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button" id="">
                                Recipes
                            </button>
                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed" type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseInventory">
                            <i class="inventory-icon"></i>
                            Inventory
                        </button>
                    </h2>
                    <div id="collapseInventory" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button" id="">
                                Summary
                            </button>
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button" id="">
                                Stock Opname
                            </button>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed" type="button"
                                data-bs-toggle="collapse" aria-expanded="false"
                                data-bs-target="#collapseCentralKitchen">
                            <i class="central-kitchen-icon"></i>
                            Central Kitchen
                        </button>
                    </h2>
                    <div id="collapseCentralKitchen" class="accordion-collapse collapse"
                         data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button" id="">
                                Stock
                            </button>
                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed" type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapsePurchasing">
                            <i class="purchasing-icon"></i>
                            Purchasing
                        </button>
                    </h2>
                    <div id="collapsePurchasing" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button" id="">
                                Supplier
                            </button>
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button" id="">
                                Purchase Order
                            </button>
                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed" type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseAccount">
                            <i class="accounting-icon"></i>
                            Accounting
                        </button>
                    </h2>
                    <div id="collapseAccount" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed" type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseFinance">
                            <i class="finance-icon"></i>
                            Finance
                        </button>
                    </h2>
                    <div id="collapseFinance" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed" type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseReport">
                            <i class="report-icon"></i>
                            Report
                        </button>
                    </h2>
                    <div id="collapseReport" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>


            </div>


        </div>
    </div>
</nav>
