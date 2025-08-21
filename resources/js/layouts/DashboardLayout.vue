<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const { props } = usePage();
const user = computed(() => props.auth?.user);

// Sidebar state
const isSidebarExpanded = ref(true);
const isLoading = ref(true);

onMounted(() => {
    // Load Sneat CSS and JS
    const loadSneatAssets = async () => {
        // Load CSS files first
        const cssFiles = [
            '/sneat/assets/vendor/fonts/boxicons.css',
            '/sneat/assets/vendor/css/core.css',
            '/sneat/assets/vendor/css/theme-default.css',
            '/sneat/assets/css/demo.css',
            '/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css',
        ];

        cssFiles.forEach((href) => {
            if (!document.querySelector(`link[href="${href}"]`)) {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = href;
                document.head.appendChild(link);
            }
        });

        // Load JS files sequentially
        const jsFiles = [
            '/sneat/assets/vendor/libs/jquery/jquery.js',
            '/sneat/assets/vendor/libs/popper/popper.js',
            '/sneat/assets/vendor/js/bootstrap.js',
            '/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
            '/sneat/assets/vendor/js/menu.js',
            '/sneat/assets/js/main.js',
        ];

        const loadScript = (src: string): Promise<void> => {
            return new Promise((resolve, reject) => {
                if (document.querySelector(`script[src="${src}"]`)) {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = src;
                script.onload = () => resolve();
                script.onerror = reject;
                document.body.appendChild(script);
            });
        };

        try {
            // Load scripts sequentially and wait for completion
            for (const src of jsFiles) {
                await loadScript(src);
            }

            // Initialize menu functionality after all scripts are loaded
            setTimeout(() => {
                initializeMenu();
                isLoading.value = false; // Set loading to false when everything is ready
            }, 200);
        } catch (error) {
            console.error('Error loading Sneat assets:', error);
            isLoading.value = false; // Set loading to false even on error
        }
    };

    const initializeMenu = () => {
        // Initialize Bootstrap components
        if (typeof (window as any).bootstrap !== 'undefined') {
            // Initialize dropdowns
            const dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            dropdownElements.forEach((el) => {
                if (!el.hasAttribute('data-bs-initialized')) {
                    new (window as any).bootstrap.Dropdown(el);
                    el.setAttribute('data-bs-initialized', 'true');
                }
            });
        }

        // Initialize menu toggle functionality
        const menuToggles = document.querySelectorAll('.menu-toggle');
        menuToggles.forEach((toggle) => {
            if (!toggle.hasAttribute('data-menu-initialized')) {
                toggle.addEventListener('click', function (this: HTMLElement, e: Event) {
                    e.preventDefault();
                    const parentLi = this.closest('.menu-item') as HTMLElement;
                    const subMenu = parentLi?.querySelector('.menu-sub') as HTMLElement;

                    if (subMenu) {
                        const isOpen = parentLi.classList.contains('open');

                        // Close all other open menus at the same level
                        const siblingMenus = parentLi.parentElement?.querySelectorAll('.menu-item.open') || [];
                        siblingMenus.forEach((sibling: Element) => {
                            if (sibling !== parentLi) {
                                sibling.classList.remove('open');
                                const siblingSubMenu = sibling.querySelector('.menu-sub') as HTMLElement;
                                if (siblingSubMenu) {
                                    siblingSubMenu.style.display = 'none';
                                }
                            }
                        });

                        // Toggle current menu
                        if (isOpen) {
                            parentLi.classList.remove('open');
                            subMenu.style.display = 'none';
                        } else {
                            parentLi.classList.add('open');
                            subMenu.style.display = 'block';
                        }
                    }
                });
                toggle.setAttribute('data-menu-initialized', 'true');
            }
        });

        // Initialize mobile menu toggle - Handle both sidebar and navbar toggles
        const mobileToggles = document.querySelectorAll('.layout-menu-toggle');
        mobileToggles.forEach((toggle) => {
            if (!toggle.hasAttribute('data-mobile-initialized')) {
                toggle.addEventListener('click', function (e: Event) {
                    e.preventDefault();
                    e.stopPropagation();

                    const layoutMenu = document.querySelector('#layout-menu');
                    const overlay = document.querySelector('.layout-overlay');

                    if (layoutMenu && overlay) {
                        const isVisible = layoutMenu.classList.contains('menu-expanded');

                        if (isVisible) {
                            layoutMenu.classList.remove('menu-expanded');
                            overlay.classList.remove('show');
                            document.body.classList.remove('menu-open');
                        } else {
                            layoutMenu.classList.add('menu-expanded');
                            overlay.classList.add('show');
                            document.body.classList.add('menu-open');
                        }
                    }
                });
                toggle.setAttribute('data-mobile-initialized', 'true');
            }
        });

        // Handle overlay click to close mobile menu
        const overlay = document.querySelector('.layout-overlay');
        if (overlay && !overlay.hasAttribute('data-overlay-initialized')) {
            overlay.addEventListener('click', function (this: HTMLElement) {
                const layoutMenu = document.querySelector('#layout-menu');
                if (layoutMenu) {
                    layoutMenu.classList.remove('menu-expanded');
                    this.classList.remove('show');
                    document.body.classList.remove('menu-open');
                }
            });
            overlay.setAttribute('data-overlay-initialized', 'true');
        }
    };

    loadSneatAssets();
});

interface MenuItem {
    label: string;
    icon?: string;
    route?: string;
    active?: boolean;
    action?: string;
    children?: MenuItem[];
    isHeader?: boolean;
}

const menuItems: MenuItem[] = [
    {
        label: 'Dashboard',
        icon: 'bx bx-home-circle',
        route: 'dashboard',
        active: route().current('dashboard'),
    },
    {
        label: 'Operasi Utama',
        isHeader: true,
    },
    {
        label: 'Transaksi',
        icon: 'bx bx-receipt',
        children: [
            { label: 'Transaksi Baru', route: 'transaksi.create', active: route().current('transaksi.create') },
            { label: 'Riwayat Transaksi', route: 'transaksi.index', active: route().current('transaksi.index') },
        ],
    },
    {
        label: 'Pembayaran',
        icon: 'bx bx-credit-card',
        children: [
            { label: 'Semua Pembayaran', route: 'pembayaran.index', active: route().current('pembayaran.index') },
            { label: 'Pembayaran Belum Selesai', route: 'pembayaran.pending', active: route().current('pembayaran.pending') },
        ],
    },
    {
        label: 'Pelanggan',
        icon: 'bx bx-user',
        route: 'pelanggan.index',
        active: route().current('pelanggan.*'),
    },
    {
        label: 'Manajemen',
        isHeader: true,
    },
    {
        label: 'Produk',
        icon: 'bx bx-package',
        children: [
            { label: 'Daftar Produk', route: 'produk.index', active: route().current('produk.*') },
            { label: 'Daftar Kategori', route: 'kategori.index', active: route().current('kategori.*') },
        ],
    },
    {
        label: 'Pengguna',
        icon: 'bx bx-user-circle',
        route: 'pengguna.index',
        active: route().current('pengguna.*'),
    },
    {
        label: 'Laporan',
        icon: 'bx bx-file',
        route: 'laporan.index',
        active: route().current('laporan.*'),
    },
    {
        label: 'Pengaturan Sistem',
        isHeader: true,
    },
    {
        label: 'Pengaturan',
        icon: 'bx bx-cog',
        children: [
            { label: 'Profil Saya', route: 'profile.edit', active: route().current('profile.*') },
            { label: 'Pengaturan Sistem', route: 'settings.index', active: route().current('settings.*') },
        ],
    },
    {
        label: 'Logout',
        icon: 'bx bx-power-off',
        action: 'logout',
    },
];

const logout = () => {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        router.post(route('logout'));
    }
};
</script>

<template>
    <div :class="['layout-wrapper layout-content-navbar', { loading: isLoading, loaded: !isLoading }]">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <Link :href="route('dashboard')" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <svg
                                width="25"
                                viewBox="0 0 25 42"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                            >
                                <defs>
                                    <path
                                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                        id="path-1"
                                    ></path>
                                    <path
                                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                        id="path-3"
                                    ></path>
                                    <path
                                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                        id="path-4"
                                    ></path>
                                    <path
                                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                        id="path-5"
                                    ></path>
                                </defs>
                                <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                                            <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                <mask id="mask-2" fill="white">
                                                    <use xlink:href="#path-1"></use>
                                                </mask>
                                                <use fill="#696cff" xlink:href="#path-1"></use>
                                                <g id="Path-3" mask="url(#mask-2)">
                                                    <use fill="#696cff" xlink:href="#path-3"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                                </g>
                                                <g id="Path-4" mask="url(#mask-2)">
                                                    <use fill="#696cff" xlink:href="#path-4"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                                </g>
                                            </g>
                                            <g
                                                id="Triangle"
                                                transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) "
                                            >
                                                <use fill="#696cff" xlink:href="#path-5"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2">Laravue KP</span>
                    </Link>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large d-block d-xl-none ms-auto">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <template v-for="(item, index) in menuItems" :key="index">
                        <!-- Menu Header -->
                        <li v-if="item.isHeader" class="menu-header small text-uppercase">
                            <span class="menu-header-text">{{ item.label }}</span>
                        </li>

                        <!-- Single Menu Item -->
                        <li v-else-if="!item.children" :class="['menu-item', { active: item.active }]">
                            <Link v-if="item.route" :href="route(item.route)" class="menu-link">
                                <i :class="[item.icon, 'menu-icon', 'tf-icons']"></i>
                                <div data-i18n="Analytics">{{ item.label }}</div>
                            </Link>
                            <a v-else-if="item.action === 'logout'" href="#" @click.prevent="logout" class="menu-link">
                                <i :class="[item.icon, 'menu-icon', 'tf-icons']"></i>
                                <div data-i18n="Logout">{{ item.label }}</div>
                            </a>
                        </li>

                        <!-- Menu with Children -->
                        <li
                            v-else
                            :class="[
                                'menu-item',
                                {
                                    open: item.children && item.children.some((child) => child.active),
                                    active: item.children && item.children.some((child) => child.active),
                                },
                            ]"
                        >
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i :class="[item.icon, 'menu-icon', 'tf-icons']"></i>
                                <div data-i18n="Layouts">{{ item.label }}</div>
                            </a>

                            <ul class="menu-sub">
                                <template v-for="(child, childIndex) in item.children" :key="childIndex">
                                    <!-- Single Sub Menu Item -->
                                    <li v-if="!child.children" :class="['menu-item', { active: child.active }]">
                                        <Link v-if="child.route" :href="route(child.route)" class="menu-link">
                                            <div data-i18n="Without menu">{{ child.label }}</div>
                                        </Link>
                                        <a v-else-if="child.action === 'logout'" href="#" @click.prevent="logout" class="menu-link">
                                            <div data-i18n="Logout">{{ child.label }}</div>
                                        </a>
                                    </li>

                                    <!-- Sub Menu with Children -->
                                    <li v-else class="menu-item">
                                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                                            <div data-i18n="Account Settings">{{ child.label }}</div>
                                        </a>

                                        <ul class="menu-sub">
                                            <li
                                                v-for="(subChild, subChildIndex) in child.children"
                                                :key="subChildIndex"
                                                :class="['menu-item', { active: subChild.active }]"
                                            >
                                                <Link v-if="subChild.route" :href="route(subChild.route)" class="menu-link">
                                                    <div data-i18n="Account">{{ subChild.label }}</div>
                                                </Link>
                                                <a v-else-if="subChild.action === 'logout'" href="#" @click.prevent="logout" class="menu-link">
                                                    <div data-i18n="Logout">{{ subChild.label }}</div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </template>
                            </ul>
                        </li>
                    </template>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav
                    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar"
                >
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-xl-0 d-xl-none me-3">
                        <a class="nav-item nav-link me-xl-4 px-0" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav align-items-center ms-auto flex-row">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="/sneat/assets/img/avatars/1.png" alt="" class="w-px-40 rounded-circle h-auto" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="me-3 flex-shrink-0">
                                                    <div class="avatar avatar-online">
                                                        <img src="/sneat/assets/img/avatars/1.png" alt="" class="w-px-40 rounded-circle h-auto" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ user?.nama }}</span>
                                                    <small class="text-muted">{{ user?.email }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <Link :href="route('profile.edit')" class="dropdown-item">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </Link>
                                    </li>
                                    <li>
                                        <Link :href="route('settings.index')" class="dropdown-item">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </Link>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" @click.prevent="logout">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl container-p-y flex-grow-1">
                        <slot />
                    </div>
                    <!-- / Content -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay"></div>
    </div>
</template>

<style scoped>
.layout-wrapper {
    height: 100vh;
}

/* Menu styling improvements */
.menu-item.open > .menu-sub {
    display: block !important;
}

.menu-item > .menu-sub {
    display: none;
}

.menu-toggle {
    cursor: pointer;
}

.menu-toggle::after {
    content: '';
    display: inline-block;
    margin-left: auto;
    vertical-align: 0.255em;
    border-top: 0.3em solid;
    border-right: 0.3em solid transparent;
    border-bottom: 0;
    border-left: 0.3em solid transparent;
    transition: transform 0.2s ease-in-out;
}

.menu-item.open > .menu-link.menu-toggle::after {
    transform: rotate(0deg);
}

/* Mobile menu improvements */
@media (max-width: 1199.98px) {
    #layout-menu {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }

    .layout-menu.menu-expanded {
        transform: translateX(0) !important;
    }

    .layout-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1030;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .layout-overlay.show {
        display: block !important;
        opacity: 1;
    }

    body.menu-open {
        overflow: hidden;
    }

    .layout-menu-toggle {
        cursor: pointer;
    }
}

/* Ensure proper loading of assets */
.layout-wrapper.loading {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.layout-wrapper.loaded {
    opacity: 1;
}
</style>
