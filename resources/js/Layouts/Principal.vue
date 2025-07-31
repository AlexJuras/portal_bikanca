<script setup>
import { ref, computed, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

defineProps({
    user: {
        type: String,
        default: "Anônimo",
    },
});

// Acessar dados globais compartilhados
const page = usePage();
const categorias = computed(() => page.props.categorias || []);

const mostraMenuMobile = ref(false);
const exibeDropdown = ref(false);
let dropdownTimeout = null;

// Configuração para limitar categorias visíveis
const MAX_CATEGORIAS_VISIVEIS = 4;
const categoriasVisiveis = computed(() => categorias.value.slice(0, MAX_CATEGORIAS_VISIVEIS));
const categoriasDropdown = computed(() => categorias.value.slice(MAX_CATEGORIAS_VISIVEIS));

const mostrarDropdown = () => {
    clearTimeout(dropdownTimeout);
    exibeDropdown.value = true;
};

const esconderDropdown = () => {
    dropdownTimeout = setTimeout(() => {
        exibeDropdown.value = false;
    }, 250); // 1 segundo
};

const dataAtual = computed(() => {
    const date = new Date();
    const data = date.toLocaleDateString("pt-BR", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
    return data.charAt(0).toUpperCase() + data.slice(1);
});

// Fecha o menu mobile quando clicar fora
const handleOutsideClick = (event) => {
    if (!event.target.closest(".mobile-menu-container")) {
        mostraMenuMobile.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleOutsideClick);
});
</script>

<style scoped>

.nav-link {
    position: relative;
    transition: all 0.3s ease;
}

.nav-link::after {
    content: "";
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background-color: #aeeeeeff;
    transition: width 0.3s ease;
}

.nav-link:hover::after {
    width: 100%;
}
</style>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-gradient-to-b from-azul-oxford to-azul-noite shadow-lg">
            <!-- Top Bar -->
            <div class="bg-azul-noite py-2">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center text-sm">
                        <div class="text-white">
                            <span>{{ dataAtual }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Header -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center">
                            <div class="bg-[url(/public/logo.png)] size-10 bg-cover bg-center"></div>
                            <div class="text-2xl text-white font-bold hover:text-celeste hover:text-3xl duration-300">IKANCA</div>
                            <div class="ml-2 text-xs text-white">
                                <div>PORTAL DE</div>
                                <div>NOTÍCIAS</div>
                            </div>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex">
                        <div class="flex space-x-8">
                            <!-- Link para todas as notícias -->
                            <Link
                                href="/noticias"
                                class="nav-link text-white hover:text-celeste px-3 py-2 text-sm font-medium hover:scale-120"
                                :class="{
                                    'border-b-2 border-azul-celeste':
                                        $page.url.startsWith('/noticias') && !$page.url.includes('/categoria/'),
                                }"
                            >
                                Todas
                            </Link>

                            <!-- Categorias visíveis diretamente na navbar -->
                            <Link
                                v-for="categoria in categoriasVisiveis"
                                :key="`nav-${categoria.id}`"
                                :href="`/noticias/categoria/${categoria.slug || categoria.id}`"
                                class="nav-link text-white hover:text-celeste px-3 py-2 text-sm font-medium hover:scale-120"
                                :class="{
                                    'border-b-2 border-azul-celeste':
                                        $page.url.includes(`/noticias/categoria/${categoria.slug || categoria.id}`),
                                }"
                            >
                                {{ categoria.nome }}
                            </Link>

                            <!-- Dropdown para categorias adicionais -->
                            <div
                                v-if="categoriasDropdown.length > 0"
                                class="relative"
                                @mouseenter="mostrarDropdown"
                                @mouseleave="esconderDropdown"
                            >
                                <button
                                    class="nav-link text-white hover:text-celeste px-3 py-2 text-sm font-medium flex items-center hover:scale-120"
                                >
                                    Mais Categorias
                                    <svg
                                        class="ml-1 w-4 h-4"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                </button>
                                <div
                                    v-show="exibeDropdown"
                                    class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-50 border"
                                >
                                    <div class="py-1">
                                        <Link
                                            v-for="categoria in categoriasDropdown"
                                            :key="`dropdown-${categoria.id}`"
                                            :href="`/noticias/categoria/${categoria.slug || categoria.id}`"
                                            class="block px-4 py-2 text-sm text-azul-oxford hover:bg-celeste hover:text-white transition-colors"
                                        >
                                            {{ categoria.nome }}
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Links fixos adicionais -->
                            <Link
                                href="/videos"
                                class="nav-link text-white hover:text-celeste px-3 py-2 text-sm font-medium hover:scale-120"
                                :class="{
                                    'border-b-2 border-azul-celeste':
                                        $page.url.startsWith('/videos'),
                                }"
                            >
                                Vídeos
                            </Link>
                        </div>
                    </nav>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button
                            @click="mostraMenuMobile = !mostraMenuMobile"
                            class="text-white hover:text-celeste"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div v-show="mostraMenuMobile" class="md:hidden bg-azul-noite">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <!-- Link para todas as notícias -->
                    <Link
                        href="/noticias"
                        class="block px-3 py-2 text-white hover:bg-azul-lazuli rounded-md"
                        :class="{
                            'bg-azul-oxford bg-opacity-50 border-l-4 border-azul-celeste':
                                $page.url.startsWith('/noticias') && !$page.url.includes('/categoria/'),
                        }"
                    >
                        Todas
                    </Link>

                    <!-- Todas as categorias no mobile -->
                    <Link
                        v-for="categoria in categorias"
                        :key="`mobile-${categoria.id}`"
                        :href="`/noticias/categoria/${categoria.slug || categoria.id}`"
                        class="block px-3 py-2 text-white hover:bg-azul-lazuli rounded-md"
                        :class="{
                            'bg-azul-oxford bg-opacity-50 border-l-4 border-azul-celeste':
                                $page.url.includes(`/noticias/categoria/${categoria.slug || categoria.id}`),
                        }"
                    >
                        {{ categoria.nome }}
                    </Link>

                    <!-- Link fixo adicional -->
                    <Link
                        href="/videos"
                        class="block px-3 py-2 text-white hover:bg-azul-lazuli rounded-md"
                        :class="{
                            'bg-azul-oxford bg-opacity-50 border-l-4 border-azul-celeste':
                                $page.url.startsWith('/videos'),
                        }"
                    >
                        Vídeos
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-azul-oxford text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Logo e Descrição -->
                    <div>
                        <h3 class="text-2xl font-bold mb-4">
                            <span class="text-celeste">B</span>ikanca
                        </h3>
                        <p class="text-cinza mb-4">
                            Portal de notícias comprometido com a informação
                            precisa e imparcial. Cobrimos os principais
                            acontecimentos do Brasil e do mundo com
                            credibilidade e transparência.
                        </p>
                    </div>

                    <!-- Contato -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4 celeste">
                            Contato
                        </h4>
                        <div class="space-y-2 text-cinza">
                            <p>contato@bikanca.com.br</p>
                            <p>redacao@bikanca.com.br</p>
                            <p>+55 (11) 9999-9999</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="bg-azul-noite py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div
                        class="flex flex-col md:flex-row justify-between items-center text-sm text-cinza"
                    >
                        <p>
                            &copy; 2025 Portal Bikanca. Todos os direitos
                            reservados.
                        </p>
                        <Link 
                            href="/admin"
                            class="text-cinza hover:text-celeste transition-colors duration-500 text-sm ml-4"
                            title="Painel Administrativo"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>