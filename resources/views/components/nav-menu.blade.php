{{--<nav class="overflow-auto md:relative md:flex md:text-sm bg-indigo-darker md:bg-transparent pin-l pin-r py-4 px-6 md:pt-10 md:pl-6 md:pr-6 -mt-1 md:mt-0 overflow-y-auto md:w-1/6 md:border-r z-40 hidden">--}}

<nav id="side-nav" class=" absolute lg:relative lg:flex lg:text-sm  bg-white lg:bg-transparent pin-l pin-r py-4 px-6 lg:pt-10 lg:pl-12 lg:pr-6 lg:mt-0 overflow-y-auto lg:w-1/5 lg:border-r z-40 hidden">
    <ul id="nav-side-list" class="list-reset mb-8 w-full">
        @include('components.nav-menu-items', ['items' => $Menu->roots()])
    </ul>
</nav>
