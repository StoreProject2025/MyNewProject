<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-[#309898]" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    
                    <!-- Categories Dropdown -->
                    <div class="nav-dropdown">
                        <button class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                            Categories
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="nav-dropdown-content">
                            @if(isset($categories) && $categories->count() > 0)
                                @foreach($categories as $category)
                                    <a href="{{ route('categories.show', $category->slug) }}" class="nav-dropdown-item">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            @else
                                <div class="nav-dropdown-item text-gray-500">
                                    No categories available
                                </div>
                            @endif
                        </div>
                    </div>

                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Products') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('deals')" :active="request()->routeIs('deals')">
                        {{ __('Deals') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Cart Link -->
                <div class="flex items-center">
                    <a href="{{ route('cart') }}" class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                        @if(auth()->check())
                            @php
                                $cartItems = auth()->user()->cart()->with('product')->get();
                                $cartCount = $cartItems->sum('quantity');
                            @endphp
                            @if($cartCount > 0)
                                <div style="position: absolute; top: -8px; right: -8px; background-color: #ef4444; color: white; font-size: 12px; font-weight: bold; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 10;">
                                    {{ $cartCount }}
                                </div>
                            @endif
                        @endif
                </a>
                </div>

                <!-- Wishlist Link -->
                <a href="{{ route('wishlist') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>

                @auth
                <div class="nav-dropdown">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                    <div class="nav-dropdown-content">
                        <a href="{{ route('profile.edit') }}" class="nav-dropdown-item">
                            Profile Settings
                        </a>
                        <a href="{{ route('orders') }}" class="nav-dropdown-item">
                            My Orders
                        </a>
                        <a href="{{ route('wishlist') }}" class="nav-dropdown-item">
                            My Wishlist
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-dropdown-item w-full text-left">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-[#309898]">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-[#309898]">Register</a>
                </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                {{ __('Products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('deals')" :active="request()->routeIs('deals')">
                {{ __('Deals') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile Settings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders')">
                    {{ __('My Orders') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('wishlist')">
                    {{ __('My Wishlist') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart')">
                    {{ __('Shopping Cart') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Login') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            </div>
            @endauth
        </div>
    </div>
</nav>

<style>
    .nav-dropdown {
        position: relative;
        display: inline-block;
    }
    .nav-dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 200px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 4px;
        z-index: 1000;
        padding: 8px 0;
    }
    .nav-dropdown:hover .nav-dropdown-content {
        display: block;
    }
    .nav-dropdown-item {
        display: block;
        padding: 8px 16px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .nav-dropdown-item:hover {
        background-color: #f3f4f6;
        color: #309898;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle mobile menu
    const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Handle dropdowns on mobile
    const dropdownButtons = document.querySelectorAll('[data-dropdown-button]');
    
    dropdownButtons.forEach(button => {
        button.addEventListener('click', () => {
            const dropdown = button.nextElementSibling;
            dropdown.classList.toggle('hidden');
        });
    });
});
</script>
