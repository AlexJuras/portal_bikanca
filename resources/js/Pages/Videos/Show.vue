<script setup>
import { ref, computed } from "vue";
import Principal from "@/Layouts/Principal.vue";
import { Link } from "@inertiajs/vue3";
defineOptions({ layout: Principal });
const props = defineProps({
    video: { type: Object, required: true },
    videosRelacionados: { type: Array, default: () => [] },
}); // Função para formatar duração
const formatarDuracao = (duracao) => {
    if (!duracao) return "N/A";
    return duracao;
};

// Função para formatar visualizações
const formatarVisualizacoes = (views) => {
    if (views >= 1000000) return `${(views / 1000000).toFixed(1)}M`;
    if (views >= 1000) return `${(views / 1000).toFixed(1)}k`;
    return views?.toString() || "0";
};
// Função para formatar data
const formatarData = (data) => {
    if (!data) return "";
    return new Date(data).toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
};
</script>
<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Breadcrumb -->
                <nav class="text-sm text-gray-600 mb-4">
                    <Link
                        href="/"
                        class="hover:text-azul-oxford transition-colors"
                        >Home</Link
                    >
                    <span class="mx-2">></span>
                    <Link
                        href="/videos"
                        class="hover:text-azul-oxford transition-colors"
                        >Vídeos</Link
                    >
                    <span class="mx-2">></span>
                    <span v-if="video.categoria" class="text-azul-oxford">{{
                        video.categoria.nome
                    }}</span>
                    <span class="mx-2">></span>
                    <span class="text-azul-oxford">{{ video.titulo }}</span>
                </nav>
                <!-- Botões de ação -->
                <div class="flex gap-2 mb-4">
                    <Link
                        :href="route('videos.edit', video.id)"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition-colors"
                    >
                        ✏️ Editar
                    </Link>
                    <Link
                        :href="route('videos.destroy', video.id)"
                        method="delete"
                        as="button"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors"
                        onclick="return confirm('Tem certeza que deseja excluir este vídeo?')"
                    >
                        🗑️ Excluir
                    </Link>
                </div>
            </div>
        </section>
        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Player e Informações do Vídeo -->
                <div class="lg:col-span-8">
                    <!-- Player do Vídeo -->
                    <div
                        class="bg-white rounded-lg shadow-sm overflow-hidden mb-6"
                    >
                        <div class="relative aspect-video bg-black">
                            <iframe
                                v-if="video.embed_url"
                                :src="video.embed_url"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                title="Player de Vídeo"
                            ></iframe>
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-white"
                            >
                                <div class="text-center">
                                    <div class="text-4xl mb-2">🎥</div>
                                    <p>Vídeo não disponível</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Informações do Vídeo -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h1 class="text-2xl font-bold text-azul-oxford mb-4">
                            {{ video.titulo }}
                        </h1>
                        <!-- Meta informações -->
                        <div
                            class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4 pb-4 border-b"
                        >
                            <span class="flex items-center gap-1">
                                👁️
                                {{ formatarVisualizacoes(video.visualizacoes) }}
                                visualizações
                            </span>
                            <span
                                v-if="video.duracao"
                                class="flex items-center gap-1"
                            >
                                ⏱️ {{ formatarDuracao(video.duracao) }}
                            </span>
                            <span
                                v-if="video.publicada_em"
                                class="flex items-center gap-1"
                            >
                                📅 {{ formatarData(video.publicada_em) }}
                            </span>
                            <span
                                v-if="video.categoria"
                                class="flex items-center gap-1"
                            >
                                🏷️ {{ video.categoria.nome }}
                            </span>
                            <span
                                v-if="video.autor"
                                class="flex items-center gap-1"
                            >
                                👤 {{ video.autor.nome }}
                            </span>
                        </div>
                        <!-- Descrição -->
                        <div class="prose max-w-none">
                            <h3
                                class="text-lg font-semibold text-azul-oxford mb-2"
                            >
                                Descrição
                            </h3>
                            <p
                                class="text-gray-700 leading-relaxed whitespace-pre-line"
                            >
                                {{ video.descricao }}
                            </p>
                        </div>
                        <!-- Link original -->
                        <div
                            v-if="video.url_externa"
                            class="mt-6 pt-4 border-t"
                        >
                            <h3
                                class="text-lg font-semibold text-azul-oxford mb-2"
                            >
                                Link Original
                            </h3>
                            <a
                                :href="video.url_externa"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-azul-oxford hover:text-azul-noite underline"
                            >
                                {{ video.url_externa }}
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Sidebar com Vídeos Relacionados -->
                <div class="lg:col-span-4">
                    <div
                        v-if="
                            videosRelacionados && videosRelacionados.length > 0
                        "
                        class="bg-white rounded-lg shadow-sm p-6"
                    >
                        <h3
                            class="text-lg font-bold text-azul-oxford mb-4 flex items-center gap-2"
                        >
                            🎬 Vídeos Relacionados
                        </h3>
                        <div class="space-y-4">
                            <article
                                v-for="videoRelacionado in videosRelacionados"
                                :key="videoRelacionado.id"
                                class="group"
                            >
                                <Link
                                    :href="
                                        route(
                                            'videos.show',
                                            videoRelacionado.id
                                        )
                                    "
                                    class="flex gap-3"
                                >
                                    <div
                                        class="relative w-24 h-16 bg-gray-100 rounded overflow-hidden flex-shrink-0"
                                    >
                                        <img
                                            :src="
                                                videoRelacionado.thumbnail_url
                                            "
                                            :alt="videoRelacionado.titulo"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                            loading="lazy"
                                        />
                                        <div
                                            class="absolute inset-0 flex items-center justify-center"
                                        >
                                            <div
                                                class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-3 h-3 text-white"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="font-medium text-azul-oxford group-hover:text-azul-noite transition-colors line-clamp-2 text-sm"
                                        >
                                            {{ videoRelacionado.titulo }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{
                                                formatarVisualizacoes(
                                                    videoRelacionado.visualizacoes
                                                )
                                            }}
                                            visualizações
                                        </p>
                                        <p
                                            v-if="videoRelacionado.duracao"
                                            class="text-xs text-gray-500"
                                        >
                                            {{
                                                formatarDuracao(
                                                    videoRelacionado.duracao
                                                )
                                            }}
                                        </p>
                                    </div>
                                </Link>
                            </article>
                        </div>
                        <!-- Link para todos os vídeos -->
                        <div class="mt-6 pt-4 border-t">
                            <Link
                                href="/videos"
                                class="text-azul-oxford hover:text-azul-noite font-medium text-sm flex items-center gap-2"
                            >
                                Ver todos os vídeos →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.prose {
    max-width: none;
}
</style>
