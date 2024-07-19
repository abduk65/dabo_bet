<aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="../dashboard/index.html" class="navbar-brand">

            <!--Logo start-->
            <div class="logo-main">
                <div class="logo-normal">
                    <svg class=" icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                            transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                        <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                            transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                        <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                            transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                        <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                            transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                    </svg>
                </div>
                <div class="logo-mini">
                    <svg class=" icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                            transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                        <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                            transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                        <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                            transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                        <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                            transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                    </svg>
                </div>
            </div>
            <!--logo End-->




            <h4 class="logo-title">Hope UI</h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list">
            <!-- Sidebar Menu Start -->
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">Home</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('inventory.index') }}">
                        <span class="item-name">Inventory</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('dailyInventoryOut.index') }}">
                        <span class="item-name">Daily Inventory Out</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('product.index') }}">
                        <span class="item-name">Product</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('recipe.index') }}">
                        <span class="item-name">Recipe</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('workOrder.index') }}">
                        <span class="item-name">Work Order</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('standardBatchVariety.index') }}">
                        <span class="item-name">Standard Batch Variety</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('brand.index') }}">
                        <span class="item-name">Brand</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('unit.index') }}">
                        <span class="item-name">Unit</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('branch.index') }}">
                        <span class="item-name">Branch</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('expense.index') }}">
                        <span class="item-name">expense</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('dailyProductionAdjustment.index') }}">
                        <span class="item-name">dailyProductionAdjustment</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('inventoryAdjustment.index') }}">
                        <span class="item-name">inventoryAdjustment</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('cashCollected.index') }}">
                        <span class="item-name">cashCollected</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('dailySales.create') }}">
                        <span class="item-name">dailySales</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('commissionRecipient.index') }}">
                        <span class="item-name">commissionRecipient</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('commission.index') }}">
                        <span class="item-name">commission</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('productType.index') }}">
                        <span class="item-name">Product Type</span>
                    </a>
                </li>
            </ul>
            <!-- Sidebar Menu End -->
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>
