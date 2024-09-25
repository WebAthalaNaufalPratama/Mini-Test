<div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link d-flex align-items-center">
        <span class="app-brand-text demo menu-text fw-bold ms-2">
            @if (!empty(trim($__env->yieldContent(1))))
                <img src="/assets/img/elements/image-2.png" alt="App Logo" style="max-height: 40px;"> <!-- Ensured the logo has a max height for consistency -->
            @else
            <img src="/assets/img/elements/image-2.png" alt="App Logo" style="max-height: 40px;"> <!-- Fallback to app name if no title -->
            @endif
        </span>
    </a>

    <!-- Toggle Button for Mobile -->
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none" aria-label="Toggle Menu">
        <i class="align-middle bx bx-chevron-left bx-sm"></i>
    </a>
</div>

<div class="menu-inner-shadow"></div>
