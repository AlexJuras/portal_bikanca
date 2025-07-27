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
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb -->
                <nav class="text-sm text-cinza mb-4">
                    <Link :href="route('noticias.index')">Notícias</Link>
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
                        <img
                            v-if="noticia.imagemCapa?.caminho"
                            :src="noticia.imagemCapa.caminho"
                            :alt="noticia.titulo"
                            class="w-full h-96 object-cover"
                        />

                        <div class="p-8">
                            <!-- Metadados -->
                            <div class="flex items-center space-x-4 mb-6">
                                <span
                                    v-if="noticia.categoria"
                                    class="bg-azul-lazuli text-white px-3 py-1 rounded-full text-sm"
                                >
                                    {{ noticia.categoria.nome }}
                                </span>
                                <span class="text-cinza text-sm">
                                    {{ formatDate(noticia.publicada_em) }}
                                </span>
                            </div>

                            <!-- Título -->
                            <h1 class="text-4xl font-bold text-azul-oxford mb-4">
                                {{ noticia.titulo }}
                            </h1>

                            <!-- Autor -->
                            <div class="flex items-center space-x-4 mb-8">
                                <img
                                    :src="userImage"
                                    :alt="noticia.autor?.nome"
                                    class="w-12 h-12 rounded-full"
                                />
                                <div>
                                    <p class="font-medium text-azul-oxford">
                                        {{ noticia.autor?.nome }}
                                    </p>
                                    <p class="text-sm text-cinza">Jornalista</p>
                                </div>
                            </div>

                            <!-- Conteúdo -->
                            <div class="prose max-w-none" v-html="noticia.conteudo">
                                
                            </div>

                            <!-- Tags -->
                            <div class="mt-8 pt-8 border-t">
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="tag in noticia.tags"
                                        :key="tag.id"
                                        class="bg-gray-100 text-cinza px-3 py-1 rounded-full text-sm"
                                    >
                                        {{ tag.nome }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4">
                    <!-- Notícias Relacionadas -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <h3 class="text-xl font-bold text-azul-oxford mb-4">
                            Notícias Relacionadas
                        </h3>
                        <div class="space-y-6">
                            <article
                                v-for="noticia in noticiasRelacionadas"
                                :key="noticia.id"
                                class="group"
                            >
                                <Link :href="route('noticias.show', noticia.id)">
                                    <img
                                        v-if="noticia.imagemCapa?.caminho"
                                        :src="noticia.imagemCapa.caminho"
                                        :alt="noticia.titulo"
                                        class="w-full h-40 object-cover rounded-lg mb-3"
                                    />
                                    <h4 class="font-medium text-azul-oxford group-hover:text-azul-lazuli transition-colors">
                                        {{ noticia.titulo }}
                                    </h4>
                                    <p class="text-sm text-cinza mt-1">
                                        {{ formatDate(noticia.publicada_em) }}
                                    </p>
                                </Link>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.prose {
    max-width: 65ch;
    color: #374151;
}

.prose p {
    margin-top: 1.25em;
    margin-bottom: 1.25em;
}

.group:hover .group-hover\:text-azul-lazuli {
    color: #007FFF;
}
</style>
