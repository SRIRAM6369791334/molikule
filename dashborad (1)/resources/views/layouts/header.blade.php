<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.svg" alt="" height="30" style="max-width: 0%;">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm.svg" alt="" height="24" style="max-width: 0%;"> <span
                            class="logo-txt">Molikule Enterprise</span>
                    </span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.svg" alt="" height="30" style="max-width: 0%;">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm.svg" alt="" height="24" style="max-width: 0%;"> <span
                            class="logo-txt">Molikule</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block" method="GET" action="{{ route('search.results') }}">
                <div class="position-relative">
                    <input type="search" id="global-search" class="form-control" name="q"
                        placeholder="Search products, categories, brands...">
                    <button class="btn btn-primary" type="submit"><i class="bx bx-search-alt align-middle"></i></button>
                    <!-- Search suggestions dropdown -->
                    <div id="search-suggestions" class="search-suggestions-dropdown d-none"></div>
                </div>
            </form>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3" method="GET" action="{{ route('search.results') }}">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q"
                                    placeholder="Search products, categories, brands..." aria-label="Search Result">
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>




            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/logo.png') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name ?? 'User' }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Search Suggestions CSS -->
<style>
    .search-suggestions-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #e1e1e1;
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 400px;
        overflow-y: auto;
        z-index: 1050;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin-top: 1px;
    }

    .search-suggestion-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f1f1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .search-suggestion-item:hover, .search-suggestion-item.active {
        background-color: #f8f9fa;
    }

    .search-suggestion-item:last-child {
        border-bottom: none;
    }

    .search-suggestion-text {
        font-weight: 500;
        color: #000 !important;
    }

    .search-suggestion-type {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .suggestion-product {
        background-color: #e3f2fd;
        color: #000 !important;
    }

    .suggestion-category {
        background-color: #f1f8e9;
        color: #000 !important;
    }

    .suggestion-brand {
        background-color: #fff3e0;
        color: #000 !important;
    }
</style>

<!-- Search JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('global-search');
        const suggestionsDropdown = document.getElementById('search-suggestions');
        let searchTimeout;

        if (searchInput && suggestionsDropdown) {
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();

                // Clear previous timeout
                clearTimeout(searchTimeout);

                // Hide suggestions if query is too short
                if (query.length < 2) {
                    hideSuggestions();
                    return;
                }

                // Debounce search requests
                searchTimeout = setTimeout(() => {
                    fetchSearchSuggestions(query);
                }, 300);
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !suggestionsDropdown.contains(e.target)) {
                    hideSuggestions();
                }
            });

            // Handle keyboard navigation
            searchInput.addEventListener('keydown', function (e) {
                const suggestions = suggestionsDropdown.querySelectorAll('.search-suggestion-item');
                let currentActive = suggestionsDropdown.querySelector('.search-suggestion-item.active');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!currentActive) {
                        suggestions[0]?.classList.add('active');
                    } else {
                        currentActive.classList.remove('active');
                        currentActive.nextElementSibling?.classList.add('active');
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (currentActive) {
                        currentActive.classList.remove('active');
                        currentActive.previousElementSibling?.classList.add('active');
                    }
                } else if (e.key === 'Enter') {
                    if (currentActive) {
                        e.preventDefault();
                        window.location.href = currentActive.dataset.url;
                    }
                } else if (e.key === 'Escape') {
                    hideSuggestions();
                }
            });
        }

        function fetchSearchSuggestions(query) {
            fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    showSuggestions(data);
                })
                .catch(error => {
                    console.error('Error fetching suggestions:', error);
                    hideSuggestions();
                });
        }

        function showSuggestions(suggestions) {
            if (suggestions.length === 0) {
                hideSuggestions();
                return;
            }

            suggestionsDropdown.innerHTML = suggestions.map(suggestion => `
                        <div class="search-suggestion-item" data-url="${suggestion.url}">
                            <span class="search-suggestion-text">${escapeHtml(suggestion.text)}</span>
                            <span class="search-suggestion-type suggestion-${suggestion.type}">${suggestion.type}</span>
                        </div>
                    `).join('');

            // Add click handlers
            suggestionsDropdown.querySelectorAll('.search-suggestion-item').forEach(item => {
                item.addEventListener('click', function () {
                    window.location.href = this.dataset.url;
                });
            });

            suggestionsDropdown.classList.remove('d-none');
        }

        function hideSuggestions() {
            if (suggestionsDropdown) {
                suggestionsDropdown.classList.add('d-none');
                suggestionsDropdown.innerHTML = '';
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    });
</script>