@if ($paginator->hasPages())
    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <nav>
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; margin: 0;">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="disabled" style="pointer-events: none;"><span style="display: block; padding: 8px 12px; border: 1px solid #ddd; color: #999;">&laquo;</span></li>
                @else
                    <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: block; padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #007bff;">&laquo;</a></li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="disabled" style="pointer-events: none;"><span style="display: block; padding: 8px 12px; border: 1px solid #ddd; color: #999;">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active" style="pointer-events: none;"><span style="display: block; padding: 8px 12px; border: 1px solid #007bff; background: #007bff; color: #fff;">{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}" style="display: block; padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #007bff;">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: block; padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #007bff;">&raquo;</a></li>
                @else
                    <li class="disabled" style="pointer-events: none;"><span style="display: block; padding: 8px 12px; border: 1px solid #ddd; color: #999;">&raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
@endif
