<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Main</li>
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-title">Logistics & Marketing</li>
                {{-- <li>
                    <a href="{{ route('pincodes.index') }}" class="waves-effect">
                        <i class="mdi mdi-map-marker-radius-outline"></i>
                        <span>Pincode Reach</span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('banners.index') }}" class="waves-effect">
                        <i class="mdi mdi-presentation"></i>
                        <span>Home Banners</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('bento-cards.index') }}" class="waves-effect">
                        <i class="mdi mdi-view-dashboard-variant"></i>
                        <span>Banners</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('certificates.index') }}" class="waves-effect">
                        <i class="mdi mdi-certificate"></i>
                        <span>Certificates</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('customers') }}" class="waves-effect">
                        <i class="mdi mdi-account-group-outline"></i>
                        <span>Customers</span>
                    </a>
                </li>
                 <li class="menu-title">Inventory Management</li>
                 <li>
                    <a href="{{ url('/brands') }}" class="waves-effect">
                        <i class="mdi mdi-certificate-outline"></i>
                        <span>Brands</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('categories.index') }}" class="waves-effect">
                        <i class="mdi mdi-tag-outline"></i>
                        <span>Categories</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('products.index') }}" class="waves-effect">
                        <i class="mdi mdi-shopping-outline"></i>
                        <span>All Products</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('product-variants.index') }}" class="waves-effect">
                        <i class="mdi mdi-layers-outline"></i>
                        <span>Product Variants</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('products.stocks') }}" class="waves-effect">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <span>Stock Management</span>
                    </a>
                </li>

                {{-- <li class="menu-title">Systems</li>
                <li>
                    <a href="{{ route('insurance.index') }}" class="waves-effect">
                        <i class="mdi mdi-shield-check-outline"></i>
                        <span>Insurance Records</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users') }}" class="waves-effect">
                        <i class="mdi mdi-account-group-outline"></i>
                        <span>Staff Management</span>
                    </a>
                </li> --}}

                <li class="menu-title">Order Lifecycle</li>
                <li>
                    <a href="{{ route('all-orders.index') }}" class="waves-effect">
                        <i class="mdi mdi-cart-outline"></i>
                        <span class="badge rounded-pill bg-success float-end">Live</span>
                        <span>All Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pending-orders') }}" class="waves-effect">
                        <i class="mdi mdi-clock-outline text-warning"></i>
                        @if(($pending_orders_count ?? 0) > 0)
                            <span class="badge rounded-pill bg-danger float-end">{{ $pending_orders_count }}</span>
                        @endif
                        <span>Pending Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('processing-orders') }}" class="waves-effect">
                        <i class="mdi mdi-cogs text-primary"></i>
                        @if(($processing_orders_count ?? 0) > 0)
                            <span class="badge rounded-pill bg-primary float-end">{{ $processing_orders_count }}</span>
                        @endif
                        <span>Processing Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dispatch-orders.index') }}" class="waves-effect">
                        <i class="mdi mdi-truck-delivery-outline text-info"></i>
                        <span>In-Transit</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('delivered-orders.index') }}" class="waves-effect">
                        <i class="mdi mdi-check-all text-success"></i>
                        <span>Delivered</span>
                    </a>
                </li>
                <li class="menu-title">Others</li>
                <li>
                    <a href="{{ route('coupons.index') }}" class="waves-effect">
                        <i class="mdi mdi-ticket-percent-outline"></i>
                        <span>Discount Coupons</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('blogs.index') }}" class="waves-effect">
                        <i class="mdi mdi-newspaper-variant-outline"></i>
                        <span>Blog Manager</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reviews.index') }}" class="waves-effect">
                        <i class="mdi mdi-star-outline"></i>
                        <span>Product Reviews</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('contacts.index') }}" class="waves-effect">
                        <i class="mdi mdi-message-text-outline"></i>
                        <span>Contact Messages</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('nexus-certifications.index') }}" class="waves-effect">
                        <i class="mdi mdi-certificate-outline"></i>
                        <span>NEXUS Enquiries</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('job-applications.index') }}" class="waves-effect">
                        <i class="mdi mdi-briefcase-account-outline"></i>
                        <span>Job Applications</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('job-positions.index') }}" class="waves-effect">
                        <i class="mdi mdi-format-list-bulleted-type"></i>
                        <span>Open Positions</span>
                    </a>
                </li>

               

                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
