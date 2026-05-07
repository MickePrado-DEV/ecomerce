@if (count($breadcrumbs) > 0)
    <nav class="mb-4 ">
        <ol class="flex flex-wrap text-sm font-medium text-gray-500 sm:text-base ">
            @foreach ($breadcrumbs as $breadcrumb)
                <li
                    class="text-sm leading-normal text-slate-700 {{ !$loop->first ? "pl-2 before:float-left before:pr-2  before:content-['/']" : '' }} ">
                    @if (isset($breadcrumb['route']))
                        <a href="{{ $breadcrumb['route'] }}" class="opacity-50">
                            {{ $breadcrumb['name'] }}
                        </a>
                    @else
                        <span class="text-slate-700">
                            {{ $breadcrumb['name'] }}
                        </span>
                    @endisset
            </li>
        @endforeach

    </ol>

    @if (count($breadcrumbs) > 1)
        <h6 class="mb-0 font-bold text-slate-700 ">
            {{ $breadcrumbs[count($breadcrumbs) - 1]['name'] }}
        </h6>


    @endif
</nav>
@endif
