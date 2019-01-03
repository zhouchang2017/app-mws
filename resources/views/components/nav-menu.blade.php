<nav class="absolute lg:relative lg:flex lg:text-sm bg-indigo-darker lg:bg-transparent pin-l pin-r py-4 px-6 lg:pt-10 lg:pl-6 lg:pr-6 -mt-1 lg:mt-0 overflow-y-auto lg:w-1/6 lg:border-r z-40 hidden">
    <ul class="list-reset mb-8 w-full">
        @include('components.nav-menu-items', ['items' => $Menu->roots()])
    </ul>
</nav>