<template>
    <div class="p-6">
        <!-- Cabeçalho do Dashboard -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-azul-oxford">Dashboard</h1>
            <p class="text-gray-600 mt-2">Gerencie seu portal de notícias com facilidade</p>
        </div>

        <!-- Seção de Ações Rápidas -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-azul-oxford mb-6">Ações Rápidas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link 
                    :href="route('admin.noticias.create')"
                    class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-6 text-center transition-colors group hover:shadow-md"
                >
                    <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-blue-900 text-lg">Nova Notícia</p>
                    <p class="text-sm text-blue-600 mt-1">Criar um novo artigo</p>
                </Link>

                <Link 
                    :href="route('admin.categorias.create')"
                    class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-6 text-center transition-colors group hover:shadow-md"
                >
                    <div class="bg-green-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-green-900 text-lg">Nova Categoria</p>
                    <p class="text-sm text-green-600 mt-1">Organizar o conteúdo</p>
                </Link>

                <Link 
                    :href="route('admin.autores.create')"
                    class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-6 text-center transition-colors group hover:shadow-md"
                >
                    <div class="bg-purple-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-purple-900 text-lg">Novo Autor</p>
                    <p class="text-sm text-purple-600 mt-1">Adicionar colaborador</p>
                </Link>

                <Link 
                    :href="route('admin.tags.create')"
                    class="bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-lg p-6 text-center transition-colors group hover:shadow-md"
                >
                    <div class="bg-orange-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 group-hover:bg-orange-200 transition-colors">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-orange-900 text-lg">Nova Tag</p>
                    <p class="text-sm text-orange-600 mt-1">Etiquetar conteúdo</p>
                </Link>
            </div>
        </div>

        <!-- Cards de Estatísticas Principais -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total de Notícias -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Notícias</p>
                        <p class="text-2xl font-bold text-azul-oxford">{{ estatisticas.total_noticias }}</p>
                    </div>
                </div>
            </div>

            <!-- Notícias Publicadas -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Publicadas</p>
                        <p class="text-2xl font-bold text-green-600">{{ estatisticas.noticias_publicadas }}</p>
                    </div>
                </div>
            </div>

            <!-- Rascunhos -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rascunhos</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ estatisticas.noticias_rascunho }}</p>
                    </div>
                </div>
            </div>

            <!-- Categorias -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500">
                <div class="flex items-center">
                    <div class="bg-indigo-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Categorias</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ estatisticas.total_categorias || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Autores -->
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Autores</p>
                        <p class="text-2xl font-bold text-purple-600">{{ estatisticas.total_autores }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas de Tempo -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Hoje -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-azul-oxford mb-1">Hoje</h3>
                        <p class="text-3xl font-bold text-azul-lazuli">{{ estatisticas.noticias_hoje }}</p>
                        <p class="text-sm text-gray-500">notícias criadas</p>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Esta Semana -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-azul-oxford mb-1">Esta Semana</h3>
                        <p class="text-3xl font-bold text-azul-lazuli">{{ estatisticas.noticias_semana }}</p>
                        <p class="text-sm text-gray-500">notícias criadas</p>
                    </div>
                    <div class="bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Este Mês -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-azul-oxford mb-1">Este Mês</h3>
                        <p class="text-3xl font-bold text-azul-lazuli">{{ estatisticas.noticias_mes }}</p>
                        <p class="text-sm text-gray-500">notícias criadas</p>
                    </div>
                    <div class="bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-azul-oxford mb-1">Tags</h3>
                        <p class="text-3xl font-bold text-azul-lazuli">{{ estatisticas.total_tags || 0 }}</p>
                        <p class="text-sm text-gray-500">tags criadas</p>
                    </div>
                    <div class="bg-orange-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Últimas Notícias -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-azul-oxford">Últimas Notícias</h2>
                    <Link 
                        :href="route('admin.noticias.index')"
                        class="text-azul-lazuli hover:text-blue-600 text-sm font-medium transition-colors"
                    >
                        Ver todas →
                    </Link>
                </div>
                
                <div class="space-y-4">
                    <article 
                        v-for="noticia in ultimasNoticias.slice(0, 5)" 
                        :key="noticia.id"
                        class="border-b pb-4 last:border-b-0 last:pb-0"
                    >
                        <div class="flex items-start space-x-3">
                            <img 
                                v-if="noticia.imagem_capa?.caminho"
                                :src="noticia.imagem_capa.caminho" 
                                :alt="noticia.titulo"
                                class="w-16 h-16 object-cover rounded-lg flex-shrink-0"
                            />
                            <div class="bg-gray-100 w-16 h-16 rounded-lg flex items-center justify-center flex-shrink-0" v-else>
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <Link 
                                    :href="route('admin.noticias.edit', noticia.id)"
                                    class="text-sm font-medium text-azul-oxford hover:text-azul-lazuli line-clamp-2 block"
                                >
                                    {{ noticia.titulo }}
                                </Link>
                                <p class="text-xs text-gray-500 mt-1">
                                    por {{ noticia.autor?.nome || 'Autor não definido' }} • {{ formatDate(noticia.created_at) }}
                                </p>
                                <div class="flex items-center mt-2 space-x-2">
                                    <span 
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                        :class="noticia.status === 'publicada' 
                                            ? 'bg-green-100 text-green-800' 
                                            : 'bg-yellow-100 text-yellow-800'"
                                    >
                                        {{ noticia.status === 'publicada' ? 'Publicada' : 'Rascunho' }}
                                    </span>
                                    <span 
                                        v-if="noticia.categoria"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                    >
                                        {{ noticia.categoria.nome }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Atividade Recente -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-azul-oxford mb-6">Atividade Recente</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                        <div class="bg-blue-100 rounded-full p-2">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ estatisticas.noticias_hoje }} notícias criadas hoje</p>
                            <p class="text-xs text-gray-500">Continue o bom trabalho!</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                        <div class="bg-green-100 rounded-full p-2">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ estatisticas.noticias_publicadas }} notícias publicadas</p>
                            <p class="text-xs text-gray-500">Conteúdo ativo no portal</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3 bg-yellow-50 rounded-lg">
                        <div class="bg-yellow-100 rounded-full p-2">
                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ estatisticas.noticias_rascunho }} rascunhos pendentes</p>
                            <p class="text-xs text-gray-500">Prontos para revisão</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg">
                        <div class="bg-purple-100 rounded-full p-2">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ estatisticas.total_autores }} autores ativos</p>
                            <p class="text-xs text-gray-500">Equipe colaborativa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorias e Tags Mais Usadas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Categorias Mais Usadas -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-azul-oxford">Categorias Mais Usadas</h2>
                    <Link 
                        :href="route('admin.categorias.index')"
                        class="text-azul-lazuli hover:text-blue-600 text-sm font-medium transition-colors"
                    >
                        Ver todas →
                    </Link>
                </div>
                
                <div class="space-y-3">
                    <div 
                        v-for="categoria in categoriasMaisUsadas.slice(0, 5)" 
                        :key="categoria.id"
                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-blue-600 font-semibold">
                                    {{ categoria.nome.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ categoria.nome }}</span>
                        </div>
                        <span class="text-sm text-gray-500 bg-white px-2 py-1 rounded-full">{{ categoria.noticias_count }} notícias</span>
                    </div>
                </div>
            </div>

            <!-- Tags Mais Usadas -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-azul-oxford">Tags Mais Usadas</h2>
                    <Link 
                        :href="route('admin.tags.index')"
                        class="text-azul-lazuli hover:text-blue-600 text-sm font-medium transition-colors"
                    >
                        Ver todas →
                    </Link>
                </div>
                
                <div class="space-y-3">
                    <div 
                        v-for="tag in tagsMaisUsadas.slice(0, 8)" 
                        :key="tag.id"
                        class="flex items-center justify-between p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-orange-100 text-orange-800">
                            #{{ tag.nome }}
                        </span>
                        <span class="text-xs text-gray-500">({{ tag.noticias_count }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import Admin from '@/Layouts/Admin.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: Admin });

const props = defineProps({
    estatisticas: {
        type: Object,
        required: true,
    },
    ultimasNoticias: {
        type: Array,
        required: true,
    },
    categoriasMaisUsadas: {
        type: Array,
        required: true,
    },
    tagsMaisUsadas: {
        type: Array,
        required: true,
    },
    autoresMaisAtivos: {
        type: Array,
        required: true,
    },
    noticiasPorMes: {
        type: Array,
        required: true,
    },
});

// Formatar data
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('pt-BR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};
</script>

<style scoped>
.text-azul-oxford {
    color: #1e3a8a;
}

.text-azul-lazuli {
    color: #007FFF;
}

.bg-azul-lazuli {
    background-color: #007FFF;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
