@if ($paginator->hasPages())
    <nav class="flex justify-center my-4">
        <ul class="flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true">
                    <span class="px-3 py-2 bg-gray-600 text-gray-400 rounded-md">‹</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">‹</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true">
                        <span class="px-3 py-2 bg-gray-600 text-gray-400 rounded-md">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page">
                                <span class="px-3 py-2 bg-blue-500 text-white rounded-md">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">›</a>
                </li>
            @else
                <li class="disabled" aria-disabled="true">
                    <span class="px-3 py-2 bg-gray-600 text-gray-400 rounded-md">›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
