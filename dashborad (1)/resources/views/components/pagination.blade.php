<div class="premium-pagination-container my-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <!-- Pagination Stats -->
        <div class="pagination-info animate-fade-in shadow-sm rounded-pill px-4 py-2 bg-white">
            <span class="text-muted small fw-medium">
                <i class="bx bx-list-ul me-1 text-primary"></i>
                @if($paginator->total() > 0)
                    Showing <span class="text-primary fw-bold">{{ $paginator->firstItem() }}</span> to 
                    <span class="text-primary fw-bold">{{ $paginator->lastItem() }}</span> of 
                    <span class="text-primary fw-bold">{{ $paginator->total() }}</span> entries
                @else
                    No entries found
                @endif
            </span>
        </div>

        <!-- Pagination Controls -->
        @if ($paginator->hasPages())
            <nav aria-label="Page navigation" class="modern-pagination">
                <ul class="pagination pagination-rounded justify-content-center mb-0">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true"><i class="bx bx-chevron-left h4 mb-0"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link animate-scale" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                                <i class="bx bx-chevron-left h4 mb-0"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link shadow-soft">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link animate-scale" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link animate-scale" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                                <i class="bx bx-chevron-right h4 mb-0"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true"><i class="bx bx-chevron-right h4 mb-0"></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
</div>

<style>
/* Modern Pagination Styling */
.modern-pagination .pagination {
    gap: 8px;
}

.modern-pagination .page-item .page-link {
    border: none;
    border-radius: 12px !important;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #495057;
    font-weight: 600;
    font-size: 14px;
    background-color: #f8f9fa;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #5156be, #4449a3);
    color: #fff;
    box-shadow: 0 4px 15px rgba(81, 86, 190, 0.3);
}

.modern-pagination .page-item:not(.active):not(.disabled) .page-link:hover {
    background-color: #e2e5e8;
    color: #5156be;
    transform: translateY(-3px);
}

.modern-pagination .page-item.disabled .page-link {
    background-color: #f8f9fa;
    color: #ced4da;
    opacity: 0.6;
}

/* Pagination Info Styling */
.pagination-info {
    border: 1px solid rgba(0, 0, 0, 0.05);
    background: #ffffff;
}

.shadow-soft {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.animate-scale {
    transition: transform 0.2s ease;
}

.animate-scale:hover {
    transform: scale(1.1);
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
}

/* Responsiveness */
@media (max-width: 576px) {
    .modern-pagination .page-item:not(.active):not(:first-child):not(:last-child) {
        display: none;
    }
}
</style>
