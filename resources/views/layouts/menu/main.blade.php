<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="background: linear-gradient(to bottom right, #003366, #006699); color: white; padding: 1.5rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">

    <x-menu.brand />

    <button class="btn btn-toggle" id="sidebarToggle" aria-expanded="false" style="background: transparent; border: none; color: white;">
        <!-- <i class="bx bx-menu" style="font-size: 1.8rem;"></i> Larger icon for better visibility -->
    </button>

    <ul class="py-1 menu-inner" id="menuItems">
        <x-menu.menu-item :active="request()->routeIs('dashboard')">
            <x-menu.menu-link :href="route('dashboard')" class="menu-link" style="color: white;">
                <i class="menu-icon tf-icons bx bx-cube-alt"></i>
                <span>Dashboard</span>
            </x-menu.menu-link>
        </x-menu.menu-item>
        <x-menu.menu-item :active="request()->routeIs('kategori')">
            <x-menu.menu-link :href="route('kategori.index')" class="menu-link" style="color: white;">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <span>Kategori</span>
            </x-menu.menu-link>
        </x-menu.menu-item>
        <x-menu.menu-item :active="request()->routeIs('buku')">
            <x-menu.menu-link :href="route('buku.index')" class="menu-link" style="color: white;">
                <i class="menu-icon tf-icons bx bx-book-alt"></i>
                <span>Buku</span>
            </x-menu.menu-link>
        </x-menu.menu-item>

        <x-menu.menu-header title="session" style="color: white; font-weight: bold; margin-top: 1rem; text-transform: uppercase; letter-spacing: 1px;" />

        <x-menu.menu-item :active="request()->routeIs('logout')">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <a class="menu-link" href="#" onclick="event.preventDefault(); confirmLogout();" style="color: white;">
                <i class="menu-icon tf-icons bx bx-log-out"></i>
                Logout
            </a>
        </x-menu.menu-item>
    </ul>
</aside>

<style>
    .menu-inner .menu-link {
        transition: background-color 0.3s, color 0.3s, padding-left 0.3s; 
        padding: 0.8rem 1.2rem;
        font-size: 0.95rem; 
        border-radius: 0.3rem; 
    }

    .menu-inner .menu-link:hover {
        background-color: rgba(255, 255, 255, 0.2); 
        color: #ffcc00; 
        padding-left: 1.5rem; 
    }

    .menu-inner .menu-item.active .menu-link {
        background-color: rgba(255, 255, 255, 0.3); 
        color: #ffcc00; 
        font-weight: bold; 
    }

    .menu-header {
        font-weight: bold;
        margin-top: 1.5rem; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
    }

    #layout-menu {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
</style>
