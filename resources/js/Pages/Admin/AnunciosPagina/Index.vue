<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Configurar Anúncios por Página</h2>
              <p class="text-gray-600 mt-1">Atribua até 3 anúncios para cada página do site</p>
            </div>
            <Link 
              :href="route('admin.anuncios.index')" 
              class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
            >
              <i class="fas fa-arrow-left mr-2"></i>
              Voltar aos Anúncios
            </Link>
          </div>

          <!-- Mensagens de Sucesso/Erro -->
          <div v-if="$page.props.flash?.success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $page.props.flash.success }}</span>
          </div>
          
          <div v-if="Object.keys($page.props.errors || {}).length > 0" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Erro!</strong>
            <ul class="mt-2 list-disc list-inside">
              <li v-for="(error, field) in $page.props.errors" :key="field">{{ error }}</li>
            </ul>
          </div>

          <!-- Grid de Páginas -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div v-for="(configuracao, paginaKey) in configuracoes" :key="paginaKey" 
                 class="bg-gray-50 rounded-lg p-6">
              
              <!-- Título da Página -->
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ configuracao.nome }}</h3>
                <span class="text-sm text-gray-500 bg-white px-2 py-1 rounded">
                  {{ configuracao.anuncios.length }}/{{ configuracao.max_anuncios }} anúncio(s)
                </span>
              </div>

              <!-- Lista de Anúncios Atribuídos -->
              <div class="space-y-3 mb-4">
                <div v-for="anuncioPagina in configuracao.anuncios" :key="anuncioPagina.id"
                     class="bg-white p-4 rounded-lg border">
                  <div class="flex items-center justify-between">
                    <div class="flex-1">
                      <div class="flex items-center space-x-3">
                        <!-- Status -->
                        <span 
                          :class="anuncioPagina.ativo 
                            ? 'bg-green-100 text-green-800' 
                            : 'bg-red-100 text-red-800'"
                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        >
                          {{ anuncioPagina.ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                        
                        <!-- Ordem -->
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 text-xs font-semibold rounded-full">
                          Posição {{ anuncioPagina.ordem }}
                        </span>
                      </div>
                      
                      <h4 class="font-medium text-gray-900 mt-2">{{ anuncioPagina.anuncio.nome }}</h4>
                      <p class="text-sm text-gray-500">{{ anuncioPagina.anuncio.tipo }}</p>
                    </div>

                    <!-- Ações -->
                    <div class="flex items-center space-x-2">
                      <!-- Toggle Ativo -->
                      <button
                        @click="toggleAtivo(anuncioPagina)"
                        :class="anuncioPagina.ativo 
                          ? 'bg-red-100 text-red-700 hover:bg-red-200' 
                          : 'bg-green-100 text-green-700 hover:bg-green-200'"
                        class="px-2 py-1 rounded text-xs font-medium transition-colors duration-200"
                      >
                        {{ anuncioPagina.ativo ? 'Desativar' : 'Ativar' }}
                      </button>

                      <!-- Remover -->
                      <button
                        @click="removerAnuncio(anuncioPagina)"
                        class="bg-red-100 text-red-700 hover:bg-red-200 px-2 py-1 rounded text-xs font-medium transition-colors duration-200"
                      >
                        Remover
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Placeholder quando não há anúncios -->
                <div v-if="configuracao.anuncios.length === 0" 
                     class="bg-white p-4 rounded-lg border-2 border-dashed border-gray-300 text-center text-gray-500">
                  <i class="fas fa-plus-circle text-2xl mb-2"></i>
                  <p>Nenhum anúncio atribuído a esta página</p>
                </div>
              </div>

              <!-- Adicionar Novo Anúncio -->
              <div v-if="configuracao.slots_disponiveis > 0" class="border-t pt-4">
                <h4 class="font-medium text-gray-700 mb-3">Adicionar Anúncio</h4>
                <form @submit.prevent="adicionarAnuncio(paginaKey)" class="space-y-3">
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <select 
                        v-model="novoAnuncio[paginaKey].anuncio_id"
                        required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                      >
                        <option value="">Selecionar anúncio</option>
                        <option v-for="anuncio in anunciosDisponiveis" :key="anuncio.id" :value="anuncio.id">
                          {{ anuncio.nome }}
                        </option>
                      </select>
                    </div>
                    <div>
                      <select 
                        v-model="novoAnuncio[paginaKey].ordem"
                        required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                      >
                        <option value="">Posição</option>
                        <option v-for="i in configuracao.max_anuncios" :key="i" :value="i"
                                :disabled="isPosicaoOcupada(paginaKey, i)">
                          Posição {{ i }} {{ isPosicaoOcupada(paginaKey, i) ? '(ocupada)' : '' }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <button 
                    type="submit"
                    :disabled="!novoAnuncio[paginaKey].anuncio_id || !novoAnuncio[paginaKey].ordem"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <i class="fas fa-plus mr-2"></i>
                    Adicionar Anúncio
                  </button>
                </form>
              </div>

              <!-- Limite Atingido -->
              <div v-else class="border-t pt-4 text-center text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Limite máximo de anúncios atingido ({{ configuracao.max_anuncios }})
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin })

const props = defineProps({
  configuracoes: Object,
  anunciosDisponiveis: Array,
})

// Estado para novos anúncios
const novoAnuncio = reactive({})

// Inicializar estado para cada página
Object.keys(props.configuracoes).forEach(paginaKey => {
  novoAnuncio[paginaKey] = {
    anuncio_id: '',
    ordem: ''
  }
})

const isPosicaoOcupada = (paginaKey, posicao) => {
  return props.configuracoes[paginaKey].anuncios.some(a => a.ordem === posicao)
}

const adicionarAnuncio = (paginaKey) => {
  const dados = {
    anuncio_id: novoAnuncio[paginaKey].anuncio_id,
    pagina: paginaKey,
    ordem: parseInt(novoAnuncio[paginaKey].ordem)
  }

  console.log('Enviando dados:', dados)

  router.post(route('admin.anuncios-pagina.store'), dados, {
    onSuccess: (page) => {
      console.log('Sucesso:', page)
      // Limpar formulário
      novoAnuncio[paginaKey].anuncio_id = ''
      novoAnuncio[paginaKey].ordem = ''
    },
    onError: (errors) => {
      console.error('Erros de validação:', errors)
      alert('Erro ao adicionar anúncio: ' + JSON.stringify(errors))
    },
    onFinish: () => {
      console.log('Requisição finalizada')
    }
  })
}

const toggleAtivo = (anuncioPagina) => {
  router.patch(route('admin.anuncios-pagina.toggle-ativo', anuncioPagina.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      anuncioPagina.ativo = !anuncioPagina.ativo
    }
  })
}

const removerAnuncio = (anuncioPagina) => {
  if (confirm(`Remover "${anuncioPagina.anuncio.nome}" desta página?`)) {
    router.delete(route('admin.anuncios-pagina.destroy', anuncioPagina.id), {
      preserveScroll: true
    })
  }
}
</script>
