<script setup>
import { ref } from "vue";
import Principal from "@/Layouts/Principal.vue";
import { Link } from "@inertiajs/vue3";

// Importar imagem do usuário
const userImage = '/images/user-placeholder.png';

defineOptions({ layout: Principal });

const props = defineProps({
    noticia: {
        type: Object,
        required: true,
    },
    noticiasRelacionadas: {
        type: Array,
        required: true,
    },
});

// Formatar data
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Funções de compartilhamento
const shareWhatsApp = () => {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(`Confira esta notícia: ${noticia.titulo}`);
    window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
};

const shareInstagram = () => {
    // Instagram não permite compartilhamento direto de links, então copiamos para clipboard
    navigator.clipboard.writeText(window.location.href);
    alert('Link copiado! Cole no Instagram Stories ou post.');
};

const shareFacebook = () => {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb -->
                <nav class="text-sm text-cinza mb-4">
                    <Link :href="route('noticias.index')" class="hover:text-azul-lazuli transition-colors">
                        Notícias
                    </Link>
                    <span class="mx-2">></span>
                    <span class="text-azul-oxford">{{ noticia.titulo }}</span>
                </nav>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Conteúdo Principal -->
                <div class="lg:col-span-8">
                    <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <!-- Imagem da Notícia -->
                        <div class="relative">
                            <img
                                v-if="noticia.imagemCapa?.caminho"
                                :src="noticia.imagemCapa.caminho"
                                :alt="noticia.titulo"
                                class="w-full h-96 object-cover"
                            />
                            <!-- Categoria overlay -->
                            <div class="absolute top-4 left-4">
                                <span
                                    v-if="noticia.categoria"
                                    class="bg-azul-lazuli text-white px-4 py-2 rounded-full text-sm font-medium shadow-lg"
                                >
                                    {{ noticia.categoria.nome }}
                                </span>
                            </div>
                        </div>

                        <div class="p-8">
                            <!-- Metadados do topo -->
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-cinza text-sm">
                                    Publicado em {{ formatDate(noticia.publicada_em) }}
                                </span>
                                
                                <!-- Compartilhamento -->
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-cinza mr-2">Compartilhar:</span>
                                    <button
                                        @click="shareWhatsApp"
                                        class="p-2 rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors"
                                        title="Compartilhar no WhatsApp"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                        </svg>
                                    </button>
                                    <button
                                        @click="shareInstagram"
                                        class="p-2 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white hover:from-purple-600 hover:to-pink-600 transition-colors"
                                        title="Compartilhar no Instagram"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </button>
                                    <button
                                        @click="shareFacebook"
                                        class="p-2 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors"
                                        title="Compartilhar no Facebook"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Título -->
                            <h1 class="text-4xl font-bold text-azul-oxford mb-6 leading-tight">
                                {{ noticia.titulo }}
                            </h1>

                            <!-- Resumo/Subtítulo se existir -->
                            <p v-if="noticia.resumo" class="text-xl text-gray-600 mb-8 leading-relaxed">
                                {{ noticia.resumo }}
                            </p>

                            <!-- Conteúdo -->
                            <div class="prose prose-lg max-w-none mb-12" v-html="noticia.conteudo">
                                
                            </div>

                            <!-- Tags -->
                            <div v-if="noticia.tags && noticia.tags.length > 0" class="mb-8 pb-8 border-b">
                                <h4 class="text-sm font-semibold text-cinza mb-3">TAGS:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="tag in noticia.tags"
                                        :key="tag.id"
                                        class="bg-gray-100 hover:bg-gray-200 text-cinza px-3 py-1 rounded-full text-sm transition-colors cursor-pointer"
                                    >
                                        {{ tag.nome }}
                                    </span>
                                </div>
                            </div>

                            <!-- Autor no final -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex items-center space-x-4">
                                    <img
                                        :src="userImage"
                                        :alt="noticia.autor?.nome"
                                        class="w-16 h-16 rounded-full"
                                    />
                                    <div>
                                        <p class="font-semibold text-azul-oxford text-lg">
                                            {{ noticia.autor?.nome }}
                                        </p>
                                        <p class="text-sm text-cinza">Jornalista</p>
                                        <p v-if="noticia.autor?.bio" class="text-sm text-gray-600 mt-2">
                                            {{ noticia.autor.bio }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Banner de Propaganda 1 -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-6 text-center">
                            <h3 class="text-lg font-bold mb-2">Publicidade</h3>
                            <p class="text-sm opacity-90">Espaço para anúncio</p>
                            <div class="mt-4 bg-white/20 rounded h-24 flex items-center justify-center">
                                <span class="text-xs">300x120</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notícias Relacionadas -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-xl font-bold text-azul-oxford mb-6 border-b pb-2">
                            Notícias Relacionadas
                        </h3>
                        <div class="space-y-6">
                            <article
                                v-for="noticia in noticiasRelacionadas"
                                :key="noticia.id"
                                class="group"
                            >
                                <Link :href="route('noticias.show', noticia.id)" class="block">
                                    <div class="relative mb-3">
                                        <img
                                            v-if="noticia.imagemCapa?.caminho"
                                            :src="noticia.imagemCapa.caminho"
                                            :alt="noticia.titulo"
                                            class="w-full h-40 object-cover rounded-lg group-hover:opacity-90 transition-opacity"
                                        />
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors rounded-lg"></div>
                                    </div>
                                    <h4 class="font-medium text-azul-oxford group-hover:text-azul-lazuli transition-colors leading-snug">
                                        {{ noticia.titulo }}
                                    </h4>
                                    <p class="text-sm text-cinza mt-2">
                                        {{ formatDate(noticia.publicada_em) }}
                                    </p>
                                </Link>
                            </article>
                        </div>
                    </div>

                    <!-- Banner de Propaganda 2 -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg p-6 text-center">
                            <h3 class="text-lg font-bold mb-2">Anúncio</h3>
                            <p class="text-sm opacity-90">Espaço para propaganda</p>
                            <div class="mt-4 bg-white/20 rounded h-32 flex items-center justify-center">
                                <span class="text-xs">300x150</span>
                            </div>
                        </div>
                    </div>

                    <!-- Widget de Newsletter -->
                    <div class="bg-azul-oxford text-white rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-3">Newsletter</h3>
                        <p class="text-sm mb-4 opacity-90">Receba as principais notícias no seu email</p>
                        <div class="space-y-3">
                            <input 
                                type="email" 
                                placeholder="Seu email"
                                class="w-full px-3 py-2 rounded bg-white/10 border border-white/20 text-white placeholder-white/70 focus:outline-none focus:border-white/50"
                            >
                            <button class="w-full bg-azul-lazuli hover:bg-blue-600 text-white py-2 rounded font-medium transition-colors">
                                Inscrever-se
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.prose {
    max-width: none;
    color: #374151;
    line-height: 1.8;
}

.prose p {
    margin-top: 1.5em;
    margin-bottom: 1.5em;
}

.prose h2, .prose h3, .prose h4 {
    color: #1e3a8a;
    font-weight: 600;
    margin-top: 2em;
    margin-bottom: 1em;
}

.prose img {
    border-radius: 0.5rem;
    margin: 2em 0;
}

.prose blockquote {
    border-left: 4px solid #007FFF;
    padding-left: 1rem;
    font-style: italic;
    background: #f8fafc;
    padding: 1rem;
    border-radius: 0.5rem;
}

.group:hover .group-hover\:text-azul-lazuli {
    color: #007FFF;
}

/* Cores personalizadas que podem não estar no Tailwind base */
.text-azul-oxford {
    color: #1e3a8a;
}

.text-azul-lazuli {
    color: #007FFF;
}

.bg-azul-oxford {
    background-color: #1e3a8a;
}

.bg-azul-lazuli {
    background-color: #007FFF;
}

.text-cinza {
    color: #6b7280;
}
</style>