<template>
  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">{{ anuncio.nome }}</h2>
              <div class="flex items-center space-x-4 mt-2">
                <span 
                  :class="anuncio.ativo 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800'"
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                >
                  {{ anuncio.ativo ? 'Ativo' : 'Inativo' }}
                </span>
                <span class="text-gray-500">{{ posicoes[anuncio.posicao] || anuncio.posicao }}</span>
                <span class="text-gray-500">•</span>
                <span class="text-gray-500">{{ paginas[anuncio.pagina] || anuncio.pagina }}</span>
              </div>
            </div>
            <div class="flex space-x-3">
              <Link 
                :href="route('admin.anuncios.edit', anuncio.id)" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
              >
                Editar
              </Link>
              <Link 
                :href="route('admin.anuncios.index')" 
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
              >
                Voltar
              </Link>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações Principais -->
            <div class="lg:col-span-2 space-y-6">
              <!-- Preview do Anúncio -->
              <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Preview do Anúncio</h3>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-white">
                  <!-- Anúncio do tipo Imagem -->
                  <div v-if="anuncio.tipo === 'imagem' && anuncio.imagem_url">
                    <a 
                      v-if="anuncio.link" 
                      :href="anuncio.link" 
                      :target="anuncio.nova_aba ? '_blank' : '_self'"
                      class="block"
                    >
                      <img 
                        :src="anuncio.imagem_url" 
                        :alt="anuncio.nome"
                        class="max-w-full h-auto rounded"
                        :style="anuncio.largura && anuncio.altura ? `max-width: ${anuncio.largura}px; max-height: ${anuncio.altura}px;` : ''"
                      />
                    </a>
                    <img 
                      v-else
                      :src="anuncio.imagem_url" 
                      :alt="anuncio.nome"
                      class="max-w-full h-auto rounded"
                      :style="anuncio.largura && anuncio.altura ? `max-width: ${anuncio.largura}px; max-height: ${anuncio.altura}px;` : ''"
                    />
                  </div>

                  <!-- Anúncio do tipo HTML -->
                  <div 
                    v-else-if="anuncio.tipo === 'html' && anuncio.html_content" 
                    v-html="anuncio.html_content"
                    class="html-preview"
                  ></div>

                  <!-- Anúncio do tipo Script -->
                  <div v-else-if="anuncio.tipo === 'script' && anuncio.script_content" class="bg-yellow-50 border border-yellow-200 rounded p-4">
                    <div class="flex items-center">
                      <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                      </svg>
                      <span class="text-yellow-800 font-medium">Script de Terceiros</span>
                    </div>
                    <p class="text-yellow-700 text-sm mt-2">
                      Por segurança, scripts não são executados no preview. O código será executado apenas no site público.
                    </p>
                  </div>

                  <!-- Nenhum conteúdo -->
                  <div v-else class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p>Nenhum conteúdo configurado</p>
                  </div>
                </div>
              </div>

              <!-- Código Fonte -->
              <div v-if="anuncio.html_content || anuncio.script_content" class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Código Fonte</h3>
                <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-sm overflow-x-auto"><code>{{ anuncio.html_content || anuncio.script_content }}</code></pre>
              </div>
            </div>

            <!-- Sidebar de Informações -->
            <div class="space-y-6">
              <!-- Estatísticas -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Estatísticas</h3>
                
                <div class="space-y-4">
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Impressões</span>
                    <span class="text-lg font-semibold text-gray-900">{{ anuncio.impressoes || 0 }}</span>
                  </div>
                  
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Cliques</span>
                    <span class="text-lg font-semibold text-gray-900">{{ anuncio.cliques || 0 }}</span>
                  </div>
                  
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">CTR</span>
                    <span class="text-lg font-semibold text-gray-900">
                      {{ anuncio.impressoes > 0 ? ((anuncio.cliques || 0) / anuncio.impressoes * 100).toFixed(2) + '%' : '0%' }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Detalhes -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detalhes</h3>
                
                <div class="space-y-3">
                  <div>
                    <span class="text-sm text-gray-600 block">Tipo</span>
                    <span class="text-sm font-medium text-gray-900">{{ tipos[anuncio.tipo] || anuncio.tipo }}</span>
                  </div>
                  
                  <div v-if="anuncio.dimensoes_formatada">
                    <span class="text-sm text-gray-600 block">Dimensões</span>
                    <span class="text-sm font-medium text-gray-900">{{ anuncio.dimensoes_formatada }}</span>
                  </div>
                  
                  <div v-if="anuncio.link">
                    <span class="text-sm text-gray-600 block">Link</span>
                    <a :href="anuncio.link" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 break-all">
                      {{ anuncio.link }}
                    </a>
                    <span v-if="anuncio.nova_aba" class="text-xs text-gray-500 block">(Nova aba)</span>
                  </div>
                  
                  <div>
                    <span class="text-sm text-gray-600 block">Ordem</span>
                    <span class="text-sm font-medium text-gray-900">{{ anuncio.ordem || 'Não definida' }}</span>
                  </div>
                </div>
              </div>

              <!-- Período de Exibição -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Período de Exibição</h3>
                
                <div class="space-y-3">
                  <div>
                    <span class="text-sm text-gray-600 block">Data de Início</span>
                    <span class="text-sm font-medium text-gray-900">
                      {{ anuncio.data_inicio ? formatarData(anuncio.data_inicio) : 'Imediato' }}
                    </span>
                  </div>
                  
                  <div>
                    <span class="text-sm text-gray-600 block">Data de Fim</span>
                    <span class="text-sm font-medium text-gray-900">
                      {{ anuncio.data_fim ? formatarData(anuncio.data_fim) : 'Indefinido' }}
                    </span>
                  </div>
                  
                  <div class="pt-2">
                    <span 
                      :class="isVigente() 
                        ? 'bg-green-100 text-green-800' 
                        : 'bg-red-100 text-red-800'"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ isVigente() ? 'Vigente' : 'Fora do período' }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Observações -->
              <div v-if="anuncio.observacoes" class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Observações</h3>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ anuncio.observacoes }}</p>
              </div>

              <!-- Metadados -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Metadados</h3>
                
                <div class="space-y-3 text-sm">
                  <div>
                    <span class="text-gray-600 block">Criado em</span>
                    <span class="font-medium text-gray-900">{{ formatarDataCompleta(anuncio.created_at) }}</span>
                  </div>
                  
                  <div>
                    <span class="text-gray-600 block">Última atualização</span>
                    <span class="font-medium text-gray-900">{{ formatarDataCompleta(anuncio.updated_at) }}</span>
                  </div>
                  
                  <div>
                    <span class="text-gray-600 block">ID</span>
                    <span class="font-medium text-gray-900 font-mono">#{{ anuncio.id }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin })

const props = defineProps({
  anuncio: Object,
  posicoes: Object,
  paginas: Object,
  tipos: Object,
})

const formatarData = (data) => {
  return new Date(data).toLocaleDateString('pt-BR')
}

const formatarDataCompleta = (data) => {
  return new Date(data).toLocaleString('pt-BR')
}

const isVigente = () => {
  const now = new Date()
  const inicio = props.anuncio.data_inicio ? new Date(props.anuncio.data_inicio) : null
  const fim = props.anuncio.data_fim ? new Date(props.anuncio.data_fim) : null
  
  const inicioValido = !inicio || inicio <= now
  const fimValido = !fim || fim >= now
  
  return props.anuncio.ativo && inicioValido && fimValido
}
</script>

<style scoped>
.html-preview {
  max-width: 100%;
  overflow: hidden;
}

.html-preview * {
  max-width: 100% !important;
}
</style>
