<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Gerenciar Anúncios</h2>
              <p class="text-gray-600 mt-1">Crie anúncios e depois atribua-os às páginas do site</p>
            </div>
            <div class="flex space-x-3">
              <Link 
                :href="route('admin.anuncios-pagina.index')" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
              >
                <i class="fas fa-cog mr-2"></i>
                Configurar Páginas
              </Link>
              <Link 
                :href="route('admin.anuncios.create')" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
              >
                <i class="fas fa-plus mr-2"></i>
                Novo Anúncio
              </Link>
            </div>
          </div>

          <!-- Filtros -->
          <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
              <select 
                v-model="filtros.tipo" 
                @change="filtrarAnuncios"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
                <option value="">Todos os tipos</option>
                <option v-for="(label, key) in tipos" :key="key" :value="key">{{ label }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select 
                v-model="filtros.ativo" 
                @change="filtrarAnuncios"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
                <option value="">Todos</option>
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
              <input
                v-model="filtros.search"
                @input="filtrarAnuncios"
                type="text"
                placeholder="Nome do anúncio..."
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
            </div>
          </div>

          <!-- Lista de Anúncios -->
          <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
              <li v-for="anuncio in anuncios.data" :key="anuncio.id" class="px-6 py-4">
                <div class="flex items-center justify-between">
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3">
                      <!-- Status -->
                      <div class="flex-shrink-0">
                        <span 
                          :class="anuncio.ativo 
                            ? 'bg-green-100 text-green-800' 
                            : 'bg-red-100 text-red-800'"
                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        >
                          {{ anuncio.ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                      </div>
                      
                      <!-- Informações -->
                      <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-medium text-gray-900 truncate">
                          {{ anuncio.nome }}
                        </h3>
                        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                          <span>{{ tipos[anuncio.tipo] || anuncio.tipo }}</span>
                          <span v-if="anuncio.dimensoes_formatada">•</span>
                          <span v-if="anuncio.dimensoes_formatada">{{ anuncio.dimensoes_formatada }}</span>
                          <span v-if="anuncio.ativo_global">•</span>
                          <span v-if="anuncio.ativo_global" class="text-green-600">Disponível para atribuição</span>
                          <span v-else class="text-red-600">•</span>
                          <span v-else class="text-red-600">Indisponível</span>
                        </div>
                        
                        <!-- Estatísticas -->
                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                          <span>{{ anuncio.impressoes || 0 }} impressões</span>
                          <span>•</span>
                          <span>{{ anuncio.cliques || 0 }} cliques</span>
                          <span v-if="anuncio.impressoes > 0">•</span>
                          <span v-if="anuncio.impressoes > 0">
                            CTR: {{ ((anuncio.cliques || 0) / anuncio.impressoes * 100).toFixed(2) }}%
                          </span>
                        </div>

                        <!-- Período de exibição -->
                        <div v-if="anuncio.data_inicio || anuncio.data_fim" class="mt-1 text-sm text-gray-500">
                          <span v-if="anuncio.data_inicio">
                            De: {{ formatarData(anuncio.data_inicio) }}
                          </span>
                          <span v-if="anuncio.data_inicio && anuncio.data_fim"> • </span>
                          <span v-if="anuncio.data_fim">
                            Até: {{ formatarData(anuncio.data_fim) }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Ações -->
                  <div class="flex items-center space-x-2">
                    <!-- Toggle Ativo -->
                    <button
                      @click="toggleAtivo(anuncio)"
                      :class="anuncio.ativo 
                        ? 'bg-red-100 text-red-700 hover:bg-red-200' 
                        : 'bg-green-100 text-green-700 hover:bg-green-200'"
                      class="px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200"
                    >
                      {{ anuncio.ativo ? 'Desativar' : 'Ativar' }}
                    </button>

                    <!-- Visualizar -->
                    <Link 
                      :href="route('admin.anuncios.show', anuncio.id)"
                      class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                    >
                      Ver
                    </Link>

                    <!-- Editar -->
                    <!-- Editar -->
                    <Link 
                      :href="route('admin.anuncios.edit', anuncio.id)"
                      class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                    >
                      Editar
                    </Link>

                    <!-- Excluir -->
                    <button
                      @click="confirmarExclusao(anuncio)"
                      class="text-red-600 hover:text-red-900 text-sm font-medium"
                    >
                      Excluir
                    </button>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <!-- Paginação -->
          <div v-if="anuncios.links" class="mt-6">
            <Pagination :links="anuncios.links" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de confirmação de exclusão -->
  <div v-if="anuncioParaExcluir" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3 text-center">
        <h3 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
        <div class="mt-2 px-7 py-3">
          <p class="text-sm text-gray-500">
            Tem certeza que deseja excluir o anúncio "{{ anuncioParaExcluir.nome }}"?
            Esta ação também removerá o anúncio de todas as páginas onde está atribuído.
          </p>
        </div>
        <div class="flex justify-center space-x-4 mt-4">
          <button
            @click="anuncioParaExcluir = null"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded font-medium"
          >
            Cancelar
          </button>
          <button
            @click="excluirAnuncio"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-medium"
          >
            Excluir
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'
import Pagination from '@/Components/Pagination.vue'

defineOptions({ layout: Admin })

const props = defineProps({
  anuncios: Object,
  tipos: Object,
})

const anuncioParaExcluir = ref(null)
const filtros = ref({
  tipo: '',
  ativo: '',
  search: ''
})

const formatarData = (data) => {
  return new Date(data).toLocaleDateString('pt-BR')
}

const filtrarAnuncios = () => {
  const params = {}
  
  if (filtros.value.tipo) params.tipo = filtros.value.tipo
  if (filtros.value.ativo) params.ativo = filtros.value.ativo
  if (filtros.value.search) params.search = filtros.value.search

  router.get(route('admin.anuncios.index'), params, {
    preserveState: true,
    preserveScroll: true
  })
}

const toggleAtivo = (anuncio) => {
  router.patch(route('admin.anuncios.toggle-ativo', anuncio.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      anuncio.ativo = !anuncio.ativo
    }
  })
}

const confirmarExclusao = (anuncio) => {
  anuncioParaExcluir.value = anuncio
}

const excluirAnuncio = () => {
  router.delete(route('admin.anuncios.destroy', anuncioParaExcluir.value.id), {
    onSuccess: () => {
      anuncioParaExcluir.value = null
    }
  })
}
</script>
