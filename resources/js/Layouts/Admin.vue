<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Administrativo -->
        <header
            class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo e Título -->
                    <div class="flex items-center">
                        <Link href="/admin" class="flex items-center">
                            <img
                                src="/public/logo.png"
                                alt="Portal Bikanca"
                                class="h-8 w-auto mr-3"
                            />
                            <div>
                                <h1 class="text-xl font-bold text-azul-oxford">
                                    Painel Administrativo
                                </h1>
                                <p class="text-sm text-gray-500">
                                    Portal Bikanca
                                </p>
                            </div>
                        </Link>
                    </div>

                    <!-- Menu Superior -->
                    <div class="hidden md:flex items-center space-x-6">
                        <Link
                            href="/"
                            target="_blank"
                            class="text-gray-600 hover:text-azul-lazuli transition-colors flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                />
                            </svg>
                            Ver Site
                        </Link>

                        <div class="relative">
                            <button
                                @click="showUserMenu = !showUserMenu"
                                class="flex items-center text-gray-600 hover:text-azul-lazuli transition-colors"
                            >
                                <svg
                                    class="w-6 h-6 mr-2"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Administrador
                                <svg
                                    class="w-4 h-4 ml-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                ref="userMenuRef"
                                v-show="showUserMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                            >
                                <a
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >Perfil</a
                                >
                                <a
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >Configurações</a
                                >
                                <hr class="my-1" />
                                <a
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >Sair</a
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Menu Mobile -->
                    <button
                        @click="showMobileMenu = !showMobileMenu"
                        class="md:hidden p-2 text-gray-600 hover:text-azul-lazuli"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <div class="flex">
            <!-- Sidebar -->
            <aside class="hidden md:flex md:flex-shrink-0">
                <div class="flex flex-col w-64">
                    <div
                        class="flex flex-col h-0 flex-1 bg-white border-r border-gray-200"
                    >
                        <div
                            class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto"
                        >
                            <nav class="mt-5 flex-1 px-2 space-y-1">
                                <!-- Dashboard -->
                                <Link
                                    href="/admin"
                                    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                    :class="
                                        $page.url === '/admin'
                                            ? 'bg-azul-lazuli text-white'
                                            : 'text-gray-600 hover:bg-gray-50 hover:text-azul-oxford'
                                    "
                                >
                                    <svg
                                        class="mr-3 h-6 w-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"
                                        />
                                    </svg>
                                    Dashboard
                                </Link>

                                <!-- Seção: Conteúdo -->
                                <div class="pt-4">
                                    <h3
                                        class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                                    >
                                        Conteúdo
                                    </h3>
                                    <div class="mt-2 space-y-1">
                                        <!-- Notícias -->
                                        <Link
                                            href="/noticias"
                                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                            :class="
                                                $page.url.startsWith(
                                                    '/noticias'
                                                )
                                                    ? 'bg-azul-lazuli text-white'
                                                    : 'text-gray-600 hover:bg-gray-50 hover:text-azul-oxford'
                                            "
                                        >
                                            <svg
                                                class="mr-3 h-6 w-6"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                                                />
                                            </svg>
                                            Notícias
                                        </Link>

                                        <!-- Categorias -->
                                        <Link
                                            href="/categorias"
                                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                            :class="
                                                $page.url.startsWith(
                                                    '/categorias'
                                                )
                                                    ? 'bg-azul-lazuli text-white'
                                                    : 'text-gray-600 hover:bg-gray-50 hover:text-azul-oxford'
                                            "
                                        >
                                            <svg
                                                class="mr-3 h-6 w-6"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 11H5m14-7H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"
                                                />
                                            </svg>
                                            Categorias
                                        </Link>

                                        <!-- Tags -->
                                        <Link
                                            :href="route('admin.tags.index')"
                                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                            :class="
                                                $page.url.startsWith('/admin/tags')
                                                    ? 'bg-azul-lazuli text-white'
                                                    : 'text-gray-600 hover:bg-gray-50 hover:text-azul-oxford'
                                            "
                                        >
                                            <svg
                                                class="mr-3 h-6 w-6"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                                />
                                            </svg>
                                            Tags
                                        </Link>
                                    </div>
                                </div>

                                <!-- Seção: Usuários -->
                                <div class="pt-4">
                                    <h3
                                        class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                                    >
                                        Usuários
                                    </h3>
                                    <div class="mt-2 space-y-1">
                                        <!-- Autores -->
                                        <Link
                                            href="/admin/autores"
                                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                                            :class="
                                                $page.url.startsWith('/admin/autores')
                                                    ? 'bg-azul-lazuli text-white'
                                                    : 'text-gray-600 hover:bg-gray-50 hover:text-azul-oxford'
                                            "
                                        >
                                            <!-- <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                            </svg> -->
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="mr-3 h-6 w-6"
                                            >
                                                <g
                                                    id="SVGRepo_bgCarrier"
                                                    stroke-width="0"
                                                ></g>
                                                <g
                                                    id="SVGRepo_tracerCarrier"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                ></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path
                                                        d="M5 20V19C5 15.134 8.13401 12 12 12V12C15.866 12 19 15.134 19 19V20"
                                                        stroke="#000000"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    ></path>
                                                    <path
                                                        d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z"
                                                        stroke="#000000"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    ></path>
                                                </g>
                                            </svg>
                                            Autores
                                        </Link>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Conteúdo Principal -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <slot />
            </main>
        </div>

        <!-- Menu Mobile -->
        <div v-show="showMobileMenu" class="fixed inset-0 flex z-40 md:hidden">
            <div
                @click="showMobileMenu = false"
                class="fixed inset-0 bg-gray-600 bg-opacity-75"
            ></div>

            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button
                        @click="showMobileMenu = false"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    >
                        <svg
                            class="h-6 w-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <nav class="mt-5 px-2 space-y-1">
                        <!-- Menu mobile content here -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { onClickOutside } from "@vueuse/core";

const showUserMenu = ref(false);
const showMobileMenu = ref(false);
const userMenuRef = ref(null);

// Fecha o menu quando clica fora
onClickOutside(userMenuRef, () => {
    showUserMenu.value = false;
});
</script>

<style scoped>
.text-azul-oxford {
    color: #1e3a8a;
}

.text-azul-lazuli {
    color: #007fff;
}

.bg-azul-lazuli {
    background-color: #007fff;
}

.hover\:text-azul-oxford:hover {
    color: #1e3a8a;
}

.hover\:text-azul-lazuli:hover {
    color: #007fff;
}
</style>
