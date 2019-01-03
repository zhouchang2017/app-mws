@foreach($items as $item)
    <li class="{{$item->hasParent() ? 'ml-2 ' : 'ml-2 mb-4 flex flex-col'}}">
        {{--<svg class="sidebar-icon w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">--}}
        {{--<path fill="var(--sidebar-icon)"--}}
        {{--d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z"--}}
        {{--/>--}}
        {{--</svg>--}}

        @if($item->hasChildren())
            <div class="flex">
                {{--<svg class="sidebar-icon w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">--}}
                    {{--<path fill="var(--sidebar-icon)"--}}
                          {{--d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z"--}}
                    {{--/>--}}
                {{--</svg>--}}
                <a class="cursor-pointer text-90 hover:text-primary font-medium mobile-home-trigger @if($item->isActive) text-primary @endif"
                   href="{!! $item->url() !!}"> {!! $item->title !!}</a>
            </div>
            <ul class="text-grey lg:text-grey-dark mt-2 list-reset leading-loose">
                @include('components.nav-menu-items', ['items' => $item->children()])
            </ul>
        @else
            <a class="cursor-pointer text-90 hover:text-primary font-medium mobile-home-trigger @if($item->isActive) text-primary @endif"
               href="{!! $item->url() !!}"> {!! $item->title !!}</a>
        @endif
    </li>
@endforeach