@if ($paginator->hasPages())
    <nav aria-label="Halaman navigasi">
        <ul class="pagination-custom" style="display: flex; list-style: none; padding: 0; margin: 0; gap: 8px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">
                        <span class="material-symbols-outlined" style="font-size: 1.2rem;">chevron_left</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <span class="material-symbols-outlined" style="font-size: 1.2rem;">chevron_left</span>
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
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <span class="material-symbols-outlined" style="font-size: 1.2rem;">chevron_right</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">
                        <span class="material-symbols-outlined" style="font-size: 1.2rem;">chevron_right</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
        .pagination-custom .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(5px);
            color: var(--color-forest-dark);
            text-decoration: none;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.8);
            box-shadow: 0 2px 10px rgba(92, 74, 61, 0.05);
            transition: all 0.2s ease;
        }

        .pagination-custom .page-item:not(.disabled):not(.active) .page-link:hover {
            background: var(--color-white);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(92, 74, 61, 0.1);
        }

        .pagination-custom .page-item.active .page-link {
            background: var(--color-forest);
            color: var(--color-white);
            border-color: var(--color-forest-dark);
        }

        .pagination-custom .page-item.disabled .page-link {
            color: var(--color-muted);
            opacity: 0.6;
            cursor: not-allowed;
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
@endif
