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
                        <!-- Dropdown de Navegação -->
                        <div class="relative">
                            <button
                                @click="showNavMenu = !showNavMenu"
                                class="flex items-center text-gray-700 hover:text-azul-lazuli transition-colors bg-gray-50 hover:bg-gray-100 px-4 py-2 rounded-md border"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                <span class="font-medium">{{ currentPageName }}</span>
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu de Navegação -->
                            <div
                                ref="navMenuRef"
                                v-show="showNavMenu"
                                class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg border border-gray-200 py-2 z-50"
                            >
                                <!-- Dashboard -->
                                <Link
                                    href="/admin"
                                    @click="showNavMenu = false"
                                    class="flex items-center px-4 py-2 text-sm transition-colors"
                                    :class="$page.url === '/admin' 
                                        ? 'bg-azul-lazuli text-white font-medium' 
                                        : 'text-gray-700 hover:bg-gray-50'"
                                >
                                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"/>
                                    </svg>
                                    Dashboard
                                </Link>

                                <!-- Divisor -->
                                <div class="border-t border-gray-100 my-2"></div>
                                
                                <!-- Seção: Conteúdo -->
                                <div class="px-4 py-1">
                                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Conteúdo</h3>
                                </div>

                                <!-- Notícias -->
                                <Link
                                    :href="route('admin.noticias.index')"
                                    @click="showNavMenu = false"
                                    class="flex items-center px-4 py-2 text-sm transition-colors"
                                    :class="$page.url.startsWith('/admin/noticias')
                                        ? 'bg-azul-lazuli text-white font-medium'
                                        : 'text-gray-700 hover:bg-gray-50'"
                                >
                                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                    Notícias
                                </Link>

                                <!-- Vídeos -->
                                <Link
                                    :href="route('admin.videos.index')"
                                    @click="showNavMenu = false"
                                    class="flex items-center px-4 py-2 text-sm transition-colors"
                                    :class="$page.url.startsWith('/admin/videos')
                                        ? 'bg-azul-lazuli text-white font-medium'
                                        : 'text-gray-700 hover:bg-gray-50'"
                                >
                                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Vídeos
                                </Link>

                                <!-- Categorias -->
                                <Link
                                    :href="route('admin.categorias.index')"
                                    @click="showNavMenu = false"
                                    class="flex items-center px-4 py-2 text-sm transition-colors"
                                    :class="$page.url.startsWith('/admin/categorias')
                                        ? 'bg-azul-lazuli text-white font-medium'
                                        : 'text-gray-700 hover:bg-gray-50'"
                                >
                                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                                    </svg>
                                    Categorias
                                </Link>

                                <!-- Tags -->
                                <Link
                                    :href="route('admin.tags.index')"
                                    @click="showNavMenu = false"
                                    class="flex items-center px-4 py-2 text-sm transition-colors"
                                    :class="$page.url.startsWith('/admin/tags')
                                        ? 'bg-azul-lazuli text-white font-medium'
                                        : 'text-gray-700 hover:bg-gray-50'"
                                >
                                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Tags
                                </Link>

                                <!-- Divisor -->
                                <div class="border-t border-gray-100 my-2"></div>
                                
                                <!-- Seção: Usuários -->
                                <div class="px-4 py-1">
                                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Usuários</h3>
                                </div>

                                <!-- Autores -->
                                <Link
                                    :href="route('admin.autores.index')"
                                    @click="showNavMenu = false"
                                    class="flex items-center px-4 py-2 text-sm transition-colors"
                                    :class="$page.url.startsWith('/admin/autores')
                                        ? 'bg-azul-lazuli text-white font-medium'
                                        : 'text-gray-700 hover:bg-gray-50'"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5">
                                        <path d="M5 20V19C5 15.134 8.13401 12 12 12V12C15.866 12 19 15.134 19 19V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Autores
                                </Link>
                            </div>
                        </div>

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
                                {{ page.props.auth?.user?.name || 'Administrador' }}
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
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                            >
                                <div class="px-4 py-2 text-sm text-gray-500 border-b">
                                    {{ page.props.auth?.user?.email }}
                                </div>
                                <a
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Perfil
                                </a>
                                <a
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                    Configurações
                                </a>
                                <hr class="my-1" />
                                <button
                                    @click="logout"
                                    class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                    </svg>
                                    Sair
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Mobile -->
                    <div class="md:hidden flex items-center space-x-4">
                        <!-- Indicador da Página Atual Mobile -->
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-700">{{ currentPageName }}</span>
                        </div>
                        
                        <button
                            @click="showMobileMenu = !showMobileMenu"
                            class="p-2 text-gray-600 hover:text-azul-lazuli"
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
            </div>
        </header>

        <!-- Breadcrumb -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-3">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <Link href="/admin" class="text-gray-500 hover:text-azul-lazuli transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                    Admin
                                </Link>
                            </li>
                            <li v-if="currentPageName !== 'Dashboard'">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 font-medium text-azul-oxford">{{ currentPageName }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    
                    <!-- Ações da Página Atual -->
                    <div class="flex items-center space-x-2">
                        <Link 
                            v-if="currentPageName === 'Notícias'" 
                            :href="route('admin.noticias.create')"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-azul-lazuli hover:bg-blue-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nova Notícia
                        </Link>
                        
                        <Link 
                            v-if="currentPageName === 'Vídeos'" 
                            :href="route('admin.videos.create')"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-azul-lazuli hover:bg-blue-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Novo Vídeo
                        </Link>
                        
                        <Link 
                            v-if="currentPageName === 'Categorias'" 
                            :href="route('admin.categorias.create')"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-azul-lazuli hover:bg-blue-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nova Categoria
                        </Link>
                        
                        <Link 
                            v-if="currentPageName === 'Tags'" 
                            :href="route('admin.tags.create')"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-azul-lazuli hover:bg-blue-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nova Tag
                        </Link>
                        
                        <Link 
                            v-if="currentPageName === 'Autores'" 
                            :href="route('admin.autores.create')"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-azul-lazuli hover:bg-blue-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Novo Autor
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <main class="w-full relative overflow-y-auto focus:outline-none">
            <slot />
        </main>

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
                    <div class="px-4 mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Navegação</h2>
                    </div>
                    
                    <nav class="px-2 space-y-1">
                        <!-- Dashboard -->
                        <Link
                            href="/admin"
                            @click="showMobileMenu = false"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                            :class="$page.url === '/admin' 
                                ? 'bg-azul-lazuli text-white' 
                                : 'text-gray-700 hover:bg-gray-50'"
                        >
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"/>
                            </svg>
                            Dashboard
                        </Link>

                        <!-- Divisor -->
                        <div class="border-t border-gray-200 my-3"></div>
                        
                        <!-- Seção: Conteúdo -->
                        <div class="px-3 py-2">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Conteúdo</h3>
                        </div>

                        <!-- Notícias -->
                        <Link
                            :href="route('admin.noticias.index')"
                            @click="showMobileMenu = false"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                            :class="$page.url.startsWith('/admin/noticias')
                                ? 'bg-azul-lazuli text-white'
                                : 'text-gray-700 hover:bg-gray-50'"
                        >
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            Notícias
                        </Link>

                        <!-- Vídeos -->
                        <Link
                            :href="route('admin.videos.index')"
                            @click="showMobileMenu = false"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                            :class="$page.url.startsWith('/admin/videos')
                                ? 'bg-azul-lazuli text-white'
                                : 'text-gray-700 hover:bg-gray-50'"
                        >
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Vídeos
                        </Link>

                        <!-- Categorias -->
                        <Link
                            :href="route('admin.categorias.index')"
                            @click="showMobileMenu = false"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                            :class="$page.url.startsWith('/admin/categorias')
                                ? 'bg-azul-lazuli text-white'
                                : 'text-gray-700 hover:bg-gray-50'"
                        >
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                            </svg>
                            Categorias
                        </Link>

                        <!-- Tags -->
                        <Link
                            :href="route('admin.tags.index')"
                            @click="showMobileMenu = false"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                            :class="$page.url.startsWith('/admin/tags')
                                ? 'bg-azul-lazuli text-white'
                                : 'text-gray-700 hover:bg-gray-50'"
                        >
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Tags
                        </Link>

                        <!-- Divisor -->
                        <div class="border-t border-gray-200 my-3"></div>
                        
                        <!-- Seção: Usuários -->
                        <div class="px-3 py-2">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Usuários</h3>
                        </div>

                        <!-- Autores -->
                        <Link
                            :href="route('admin.autores.index')"
                            @click="showMobileMenu = false"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                            :class="$page.url.startsWith('/admin/autores')
                                ? 'bg-azul-lazuli text-white'
                                : 'text-gray-700 hover:bg-gray-50'"
                        >
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5">
                                <path d="M5 20V19C5 15.134 8.13401 12 12 12V12C15.866 12 19 15.134 19 19V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Autores
                        </Link>

                        <!-- Divisor -->
                        <div class="border-t border-gray-200 my-3"></div>

                        <!-- Ver Site -->
                        <Link
                            href="/"
                            target="_blank"
                            @click="showMobileMenu = false"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors"
                        >
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Ver Site
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { onClickOutside } from "@vueuse/core";

const showUserMenu = ref(false);
const showMobileMenu = ref(false);
const showNavMenu = ref(false);
const userMenuRef = ref(null);
const navMenuRef = ref(null);

const page = usePage();

// Método para logout
const logout = () => {
    router.post('/logout');
};

// Determinar o nome da página atual
const currentPageName = computed(() => {
    const url = page.url;
    
    if (url === '/admin') return 'Dashboard';
    if (url.startsWith('/admin/noticias')) return 'Notícias';
    if (url.startsWith('/admin/videos')) return 'Vídeos';
    if (url.startsWith('/admin/categorias')) return 'Categorias';
    if (url.startsWith('/admin/tags')) return 'Tags';
    if (url.startsWith('/admin/autores')) return 'Autores';
    
    return 'Admin';
});

// Fecha os menus quando clica fora
onClickOutside(userMenuRef, () => {
    showUserMenu.value = false;
});

onClickOutside(navMenuRef, () => {
    showNavMenu.value = false;
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
