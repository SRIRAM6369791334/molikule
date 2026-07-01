<!-- main header -->
        <header class="main-header">
            <!-- header-top -->
            <!-- <div class="header-top">
                <div class="auto-container">
                    <div class="top-inner">
                        <ul class="info-list">
                            <li><i class="icon-2"></i>Open Hours: <span>Mon - Fri 8am - 6pm</span></li>
                            <li><i class="icon-3"></i><button type="button">Live Chat</button></li>
                            <li><i class="icon-4"></i><a href="tel:00000000000">Call Support</a></li>
                        </ul>
                        <div class="right-column">
                            <div class="text mr_30">
                                <i class="icon-5"></i>
                                <p>Fast and Free Shipping all over Europe</p>
                            </div>
                            <div class="language-picker js-language-picker mr_30" data-trigger-class="btn btn--subtle">
                                <form action="index-2.html" class="language-picker__form">
                                    <label for="language-picker-select">Select your language</label>
                                    <select name="language-picker-select" id="language-picker-select">
                                        <option lang="de" value="deutsch">DE</option>
                                        <option lang="en" value="english" selected>EN</option>
                                        <option lang="fr" value="francais">FR</option>
                                        <option lang="it" value="italiano">IT</option>
                                    </select>
                                </form>
                            </div>
                            <div class="select-box">
                                <select class="wide">
                                   <option data-display="USD">USD</option>
                                   <option value="1">UAD</option>
                                   <option value="2">RM</option>
                                   <option value="3">GBP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- header-upper -->
            <div class="header-upper">
                <div class="auto-container">
                    <div class="upper-inner">
                        <figure class="logo-box"><a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo1.png') }}" alt=""></a></figure>
                        <form method="get" action="{{ route('shop') }}" class="search-form">
                            <div class="search-area glass-effect">
                                <div class="category-box">
                                    <div class="select-box">
                                        <select class="wide" name="category">
                                           <option data-display="All Categories" value="">All Categories</option>
                                           @foreach($headerCategories as $cat)
                                               <option value="{{ $cat->slug }}">{{ $cat->category_name }}</option>
                                               @foreach($cat->children as $child)
                                                   <option value="{{ $child->slug }}">&nbsp;&nbsp;&nbsp;-- {{ $child->category_name }}</option>
                                               @endforeach
                                           @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="search-box">
                                    <div class="form-group">
                                        <input type="search" name="search" placeholder="Search Products">
                                        <button type="submit"><i class="icon-9"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <ul class="option-list">
                            {{-- <li><a href="{{ route('shop') }}"><i class="icon-1"></i></a></li> --}}
                            <li><a href="{{ route('wishlist') }}"><i class="icon-7"></i><span>{{ wishlistCount() }}</span></a></li>
                            <li><a href="{{ route('cart') }}"><i class="icon-6"></i><span>{{ cartCount() }}</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- header-lower -->
            <div class="header-lower">
                <div class="auto-container">
                    <div class="outer-box">
                        <div class="category-box"style="display:none;">
                            <span class="text"><i class="fas fa-bars"></i>All Categories</span>
                            <ul class="category-list clearfix">
                                @foreach($headerCategories as $category)
                                <li class="category-dropdown">
                                    <a href="{{ route('shop', ['category' => $category->category_id]) }}">{{ $category->category_name }}</a>
                                    @if($category->children->count() > 0)
                                    <div class="list-inner">
                                        <div class="inner-box clearfix">
                                            @foreach($category->children->chunk(5) as $chunk)
                                            <div class="single-column">
                                                <ul>
                                                    @foreach($chunk as $child)
                                                        <li><a href="{{ route('shop', ['category' => $child->category_id]) }}">{{ $child->category_name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </li>
                                @endforeach
                                {{-- <li><a href="{{ route('shop') }}">Steering Wheel</a></li>
                                <li><a href="{{ route('shop') }}">Charging & Battery</a></li> --}}
                            </ul>
                        </div>
                        <div class="menu-area">
                            <!--Mobile Navigation Toggler-->
                            <div class="mobile-nav-toggler">
                                <i class="icon-bar"></i>
                                <i class="icon-bar"></i>
                                <i class="icon-bar"></i>
                            </div>
                            <nav class="main-menu navbar-expand-md navbar-light clearfix">
                                <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                    <ul class="navigation clearfix">
                                        <li class="{{ Request::routeIs('home') || request()->is('/') ? 'current' : '' }}"><a href="/">Home</a>
                                        </li> 
                                        <li class="{{ Request::routeIs('categories') ? 'current' : '' }}"><a href="{{ route('categories') }}">Categories</a></li>
                                        <li class="{{ Request::routeIs('brands') ? 'current' : '' }}"><a href="{{ route('brands') }}">Brands</a></li>
                                        <li class="{{ Request::routeIs('shop') || request()->is('shop/*') ? 'current' : '' }}"><a href="{{ route('shop') }}">Shop</a></li> 
                                        
                                        <li class="{{ Request::routeIs('about') ? 'current' : '' }}"><a href="{{ route('about') }}">About Us</a></li>
                                        
                                        <li class="{{ Request::routeIs('contact') ? 'current' : '' }}"><a href="{{ route('contact') }}">Contact</a></li> 
                                        

                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div class="menu-right-content">
                            @auth
                                <div class="btn-box"><a href="{{ route('my-account') }}"><i class="icon-25"></i>My Account</a></div>
                            @else
                                <div class="btn-box"><a href="{{ route('login') }}"><i class="icon-25"></i>Login</a></div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!--sticky Header-->
            <div class="sticky-header">
                <div class="auto-container">
                    <div class="outer-box">
                        <div class="category-box" style="display:none;">
                            <span class="text"><i class="fas fa-bars"></i>All Categories</span>
                            <ul class="category-list clearfix">
                                @foreach($headerCategories as $category)
                                <li class="category-dropdown">
                                    <a href="{{ route('shop', ['category' => $category->category_id]) }}">{{ $category->category_name }}</a>
                                    @if($category->children->count() > 0)
                                    <div class="list-inner">
                                        <div class="inner-box clearfix">
                                            @foreach($category->children->chunk(5) as $chunk)
                                            <div class="single-column">
                                                <ul>
                                                    @foreach($chunk as $child)
                                                        <li><a href="{{ route('shop', ['category' => $child->category_id]) }}">{{ $child->category_name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </li>
                                @endforeach
                                {{-- <li><a href="{{ route('shop') }}">Steering Wheel</a></li>
                                <li><a href="{{ route('shop') }}">Charging & Battery</a></li> --}}
                            </ul>
                        </div>
                        <div class="menu-area">
                            <nav class="main-menu clearfix">
                                <!--Keep This Empty / Menu will come through Javascript-->
                            </nav>
                        </div>
                        <div class="menu-right-content">
                            @auth
                                <div class="btn-box"><a href="{{ route('my-account') }}"><i class="icon-25"></i>My Account</a></div>
                            @else
                                <div class="btn-box"><a href="{{ route('login') }}"><i class="icon-25"></i>Login</a></div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- main-header end -->
<style>
@media only screen and (max-width: 1024px) {
    .main-header .outer-box{
        padding: 0px !important;
    }
}
</style