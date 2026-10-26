{{-- Dark pagination for Livewire lists: {{ $items->links('livewire.cp-pagination') }} --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-400">
            Showing <span class="font-semibold text-white">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-white">{{ $paginator->lastItem() }}</span>
            of <span class="font-semibold text-white">{{ $paginator->total() }}</span>
        </p>

        <div class="flex items-center gap-1">
            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" @disabled($paginator->onFirstPage())
                    class="h-9 px-3 rounded-lg text-sm font-medium text-gray-300 ring-1 ring-white/10 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed">
                ‹ Prev
            </button>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="h-9 px-2 flex items-center text-sm text-gray-500">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <button type="button" wire:key="page-{{ $page }}" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                @class([
                                    'h-9 min-w-[2.25rem] px-2 rounded-lg text-sm font-semibold',
                                    'bg-emerald-400 text-gray-950' => $page == $paginator->currentPage(),
                                    'text-gray-300 hover:bg-white/10' => $page != $paginator->currentPage(),
                                ])
                                @if ($page == $paginator->currentPage()) aria-current="page" @endif>
                            {{ $page }}
                        </button>
                    @endforeach
                @endif
            @endforeach

            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" @disabled(! $paginator->hasMorePages())
                    class="h-9 px-3 rounded-lg text-sm font-medium text-gray-300 ring-1 ring-white/10 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed">
                Next ›
            </button>
        </div>
    </nav>
@endif
