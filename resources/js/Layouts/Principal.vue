<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
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

// Função para toggle do menu mobile com debug
const toggleMobileMenu = () => {
    console.log('Menu mobile clicado. Estado atual:', mostraMenuMobile.value);
    mostraMenuMobile.value = !mostraMenuMobile.value;
    console.log('Novo estado:', mostraMenuMobile.value);
};

// Funções de compartilhamento do site
const compartilharFacebookSite = () => {
    const url = window.location.origin;
    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    window.open(facebookUrl, '_blank', 'width=600,height=400');
};

const compartilharTwitterSite = () => {
    const url = window.location.origin;
    const texto = `Portal Bikanca - Notícias em tempo real`;
    const twitterUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(texto)}`;
    window.open(twitterUrl, '_blank', 'width=600,height=400');
};

const compartilharWhatsAppSite = () => {
    const url = window.location.origin;
    const texto = `Portal Bikanca - Notícias em tempo real\n${url}`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(texto)}`;
    window.open(whatsappUrl, '_blank');
};

const compartilharInstagramSite = () => {
    // Instagram não permite compartilhamento direto via URL, então vamos copiar o link
    copiarLinkSite();
    alert('Link copiado! Você pode colá-lo no Instagram.');
};

const copiarLinkSite = async () => {
    const url = window.location.origin;
    try {
        await navigator.clipboard.writeText(url);
        alert('Link do site copiado para a área de transferência!');
    } catch (err) {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Link do site copiado para a área de transferência!');
    }
};

onMounted(() => {
    document.addEventListener("click", handleOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener("click", handleOutsideClick);
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
    background-color: #8a8eff;
    transition: width 0.3s ease;
}

.nav-link:hover::after {
    width: 100%;
}
</style>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-[rgba(33,34,175,255)] shadow-lg border-b-2 border-[#4c54ff]">
            <!-- Top Bar -->
            <div class="bg-[#4c54ff] py-2">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center text-sm">
                        <div class="text-white font-medium">
                            <span>{{ dataAtual }}</span>
                        </div>
                        
                        <!-- Compartilhamento do Site -->
                        <div class="flex items-center space-x-1">
                            <span class="text-white text-xs mr-2 hidden sm:inline">Compartilhar:</span>
                            
                            <!-- Facebook -->
                            <button 
                                @click="compartilharFacebookSite"
                                class="p-1.5 text-white hover:text-blue-200 transition-colors"
                                title="Compartilhar no Facebook"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </button>
                            
                            <!-- X (Twitter) -->
                            <button 
                                @click="compartilharTwitterSite"
                                class="p-1.5 text-white hover:text-blue-300 transition-colors"
                                title="Compartilhar no X (Twitter)"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </button>
                            
                            <!-- WhatsApp -->
                            <button 
                                @click="compartilharWhatsAppSite"
                                class="p-1.5 text-white hover:text-green-300 transition-colors"
                                title="Compartilhar no WhatsApp"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                            </button>
                            
                            <!-- Instagram -->
                            <button 
                                @click="compartilharInstagramSite"
                                class="p-1.5 text-white hover:text-pink-300 transition-colors"
                                title="Compartilhar no Instagram"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </button>
                            
                            <!-- Separador -->
                            <div class="w-px h-4 bg-white/30 mx-2"></div>
                            
                            <!-- Copiar Link -->
                            <button 
                                @click="copiarLinkSite"
                                class="p-1.5 text-white hover:text-gray-300 transition-colors"
                                title="Copiar link do site"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Header -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-24">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center hover:scale-105 transition-transform duration-300">
                            <div class="bg-[url(/public/logo.png)] w-22 h-22 bg-contain bg-center bg-no-repeat"></div>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex">
                        <div class="flex space-x-8">
                            <!-- Link para todas as notícias -->
                            <Link
                                href="/noticias"
                                class="nav-link text-white hover:text-[#8a8eff] px-3 py-2 text-sm font-medium hover:scale-110"
                                :class="{
                                    'border-b-2 border-[#8a8eff]':
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
                                class="nav-link text-white hover:text-[#8a8eff] px-3 py-2 text-sm font-medium hover:scale-110"
                                :class="{
                                    'border-b-2 border-[#8a8eff]':
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
                                    class="nav-link text-white hover:text-[#8a8eff] px-3 py-2 text-sm font-medium flex items-center hover:scale-110"
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
                                            class="block px-4 py-2 text-sm text-[rgba(33,34,175,255)] hover:bg-[#8a8eff] hover:text-white transition-colors"
                                        >
                                            {{ categoria.nome }}
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Links fixos adicionais -->
                            <Link
                                href="/videos"
                                class="nav-link text-white hover:text-[#8a8eff] px-3 py-2 text-sm font-medium hover:scale-110"
                                :class="{
                                    'border-b-2 border-[#8a8eff]':
                                        $page.url.startsWith('/videos'),
                                }"
                            >
                                Vídeos
                            </Link>
                        </div>
                    </nav>

                    <!-- Mobile menu button -->
                    <div class="md:hidden mobile-menu-container">
                        <button
                            @click="toggleMobileMenu"
                            class="text-white hover:text-[#8a8eff] p-2 focus:outline-none focus:ring-2 focus:ring-[#8a8eff] relative z-10"
                            type="button"
                            aria-label="Abrir menu"
                        >
                            <!-- Ícone Hambúrguer -->
                            <svg
                                v-show="!mostraMenuMobile"
                                class="h-6 w-6 transition-transform duration-200"
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
                            
                            <!-- Ícone X -->
                            <svg
                                v-show="mostraMenuMobile"
                                class="h-6 w-6 transition-transform duration-200"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div 
                v-show="mostraMenuMobile" 
                class="md:hidden bg-[#1a1b7a] mobile-menu-container transition-all duration-300 ease-in-out"
                style="transform-origin: top"
            >
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <!-- Link para todas as notícias -->
                    <Link
                        href="/noticias"
                        class="block px-3 py-2 text-white hover:bg-[#4c54ff] rounded-md"
                        :class="{
                            'bg-[rgba(33,34,175,255)] bg-opacity-70 border-l-4 border-[#8a8eff]':
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
                        class="block px-3 py-2 text-white hover:bg-[#4c54ff] rounded-md"
                        :class="{
                            'bg-[rgba(33,34,175,255)] bg-opacity-70 border-l-4 border-[#8a8eff]':
                                $page.url.includes(`/noticias/categoria/${categoria.slug || categoria.id}`),
                        }"
                    >
                        {{ categoria.nome }}
                    </Link>

                    <!-- Link fixo adicional -->
                    <Link
                        href="/videos"
                        class="block px-3 py-2 text-white hover:bg-[#4c54ff] rounded-md"
                        :class="{
                            'bg-[rgba(33,34,175,255)] bg-opacity-70 border-l-4 border-[#8a8eff]':
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
        <footer class="bg-[rgba(33,34,175,255)] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Logo e Descrição -->
                    <div class="bg-[url(/public/logo.png)] size-40 bg-cover bg-center"></div>

                    <!-- Contato -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-white">
                            Contato
                        </h4>
                        <div class="space-y-2 text-white">
                            <p>bikancajornalismo@gmail.com</p>
                            <p>Whatsapp: (86) 9 9419-6377</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="bg-[#1a1b7a] py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div
                        class="flex flex-col md:flex-row justify-between items-center text-sm text-white"
                    >
                        <p>
                            &copy; 2025 Portal Bikanca. Todos os direitos
                            reservados.
                        </p>
                        <Link 
                            href="/admin"
                            class="text-white hover:text-[#8a8eff] transition-colors duration-500 text-sm ml-4"
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