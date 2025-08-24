<template>
  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Editar Anúncio</h2>
              <p class="text-gray-600 mt-1">Modifique as configurações do anúncio</p>
            </div>
            <div class="flex space-x-3">
              <Link 
                :href="route('admin.anuncios.show', anuncio.id)" 
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
              >
                Visualizar
              </Link>
              <Link 
                :href="route('admin.anuncios.index')" 
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
              >
                Voltar
              </Link>
            </div>
          </div>

          <!-- Formulário -->
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Informações Básicas -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nome -->
                <div class="md:col-span-2">
                  <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                    Nome do Anúncio *
                  </label>
                  <input
                    id="nome"
                    v-model="form.nome"
                    type="text"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Ex: Banner Homepage - Topo"
                  />
                  <div v-if="$page.props.errors.nome" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.nome }}
                  </div>
                </div>

                <!-- Posição -->
                <div>
                  <label for="posicao" class="block text-sm font-medium text-gray-700 mb-1">
                    Posição *
                  </label>
                  <select
                    id="posicao"
                    v-model="form.posicao"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  >
                    <option value="">Selecione uma posição</option>
                    <option v-for="(label, key) in posicoes" :key="key" :value="key">
                      {{ label }}
                    </option>
                  </select>
                  <div v-if="$page.props.errors.posicao" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.posicao }}
                  </div>
                </div>

                <!-- Página -->
                <div>
                  <label for="pagina" class="block text-sm font-medium text-gray-700 mb-1">
                    Página *
                  </label>
                  <select
                    id="pagina"
                    v-model="form.pagina"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  >
                    <option value="">Selecione uma página</option>
                    <option v-for="(label, key) in paginas" :key="key" :value="key">
                      {{ label }}
                    </option>
                  </select>
                  <div v-if="$page.props.errors.pagina" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.pagina }}
                  </div>
                </div>

                <!-- Tipo -->
                <div>
                  <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">
                    Tipo de Anúncio *
                  </label>
                  <select
                    id="tipo"
                    v-model="form.tipo"
                    required
                    @change="limparCamposTipo"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  >
                    <option value="">Selecione um tipo</option>
                    <option v-for="(label, key) in tipos" :key="key" :value="key">
                      {{ label }}
                    </option>
                  </select>
                  <div v-if="$page.props.errors.tipo" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.tipo }}
                  </div>
                </div>

                <!-- Ordem -->
                <div>
                  <label for="ordem" class="block text-sm font-medium text-gray-700 mb-1">
                    Ordem de Exibição
                  </label>
                  <input
                    id="ordem"
                    v-model="form.ordem"
                    type="number"
                    min="0"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Automático se não informado"
                  />
                  <div v-if="$page.props.errors.ordem" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.ordem }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Conteúdo do Anúncio -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Conteúdo do Anúncio</h3>

              <!-- Anúncio de Imagem -->
              <div v-if="form.tipo === 'imagem'" class="space-y-4">
                <!-- Imagem Atual -->
                <div v-if="anuncio.imagem" class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Imagem Atual</label>
                  <img :src="anuncio.imagem_url" :alt="anuncio.nome" class="max-w-xs rounded-lg shadow-md" />
                </div>

                <!-- Upload de Nova Imagem -->
                <div>
                  <label for="imagem" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ anuncio.imagem ? 'Substituir Imagem' : 'Imagem *' }}
                  </label>
                  <input
                    id="imagem"
                    type="file"
                    accept="image/*"
                    @change="handleImageUpload"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                  <p class="text-sm text-gray-500 mt-1">
                    Formatos aceitos: JPEG, PNG, JPG, GIF, WebP (máx. 2MB)
                  </p>
                  <div v-if="$page.props.errors.imagem" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.imagem }}
                  </div>
                </div>

                <!-- Link -->
                <div>
                  <label for="link" class="block text-sm font-medium text-gray-700 mb-1">
                    Link de Destino
                  </label>
                  <input
                    id="link"
                    v-model="form.link"
                    type="url"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="https://exemplo.com"
                  />
                  <div v-if="$page.props.errors.link" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.link }}
                  </div>
                </div>

                <!-- Nova Aba -->
                <div class="flex items-center">
                  <input
                    id="nova_aba"
                    v-model="form.nova_aba"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  />
                  <label for="nova_aba" class="ml-2 block text-sm text-gray-700">
                    Abrir link em nova aba
                  </label>
                </div>
              </div>

              <!-- Anúncio HTML -->
              <div v-else-if="form.tipo === 'html'" class="space-y-4">
                <div>
                  <label for="html_content" class="block text-sm font-medium text-gray-700 mb-1">
                    Código HTML *
                  </label>
                  <textarea
                    id="html_content"
                    v-model="form.html_content"
                    rows="8"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Cole aqui o código HTML do anúncio..."
                  ></textarea>
                  <div v-if="$page.props.errors.html_content" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.html_content }}
                  </div>
                </div>
              </div>

              <!-- Anúncio Script -->
              <div v-else-if="form.tipo === 'script'" class="space-y-4">
                <div>
                  <label for="script_content" class="block text-sm font-medium text-gray-700 mb-1">
                    Código JavaScript *
                  </label>
                  <textarea
                    id="script_content"
                    v-model="form.script_content"
                    rows="8"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Cole aqui o código JavaScript (ex: Google Ads, scripts de terceiros)..."
                  ></textarea>
                  <p class="text-sm text-yellow-600 mt-1">
                    ⚠️ Cuidado: Execute apenas scripts confiáveis
                  </p>
                  <div v-if="$page.props.errors.script_content" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.script_content }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Dimensões -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Dimensões</h3>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Dimensões Personalizadas -->
                <div>
                  <label for="dimensoes" class="block text-sm font-medium text-gray-700 mb-1">
                    Dimensões (texto)
                  </label>
                  <input
                    id="dimensoes"
                    v-model="form.dimensoes"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Ex: 300x250"
                  />
                </div>

                <!-- Largura -->
                <div>
                  <label for="largura" class="block text-sm font-medium text-gray-700 mb-1">
                    Largura (px)
                  </label>
                  <input
                    id="largura"
                    v-model="form.largura"
                    type="number"
                    min="1"
                    max="2000"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                </div>

                <!-- Altura -->
                <div>
                  <label for="altura" class="block text-sm font-medium text-gray-700 mb-1">
                    Altura (px)
                  </label>
                  <input
                    id="altura"
                    v-model="form.altura"
                    type="number"
                    min="1"
                    max="2000"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                </div>
              </div>
            </div>

            <!-- Configurações de Exibição -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Exibição</h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Data de Início -->
                <div>
                  <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">
                    Data de Início
                  </label>
                  <input
                    id="data_inicio"
                    v-model="form.data_inicio"
                    type="datetime-local"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                  <p class="text-sm text-gray-500 mt-1">Deixe vazio para ativar imediatamente</p>
                </div>

                <!-- Data de Fim -->
                <div>
                  <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">
                    Data de Fim
                  </label>
                  <input
                    id="data_fim"
                    v-model="form.data_fim"
                    type="datetime-local"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  />
                  <p class="text-sm text-gray-500 mt-1">Deixe vazio para exibir indefinidamente</p>
                </div>

                <!-- Ativo -->
                <div class="md:col-span-2">
                  <div class="flex items-center">
                    <input
                      id="ativo"
                      v-model="form.ativo"
                      type="checkbox"
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label for="ativo" class="ml-2 block text-sm text-gray-700">
                      Anúncio ativo (visível no site)
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Observações -->
            <div>
              <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">
                Observações
              </label>
              <textarea
                id="observacoes"
                v-model="form.observacoes"
                rows="3"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="Anotações internas sobre este anúncio..."
              ></textarea>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
              <Link 
                :href="route('admin.anuncios.index')"
                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200"
              >
                {{ processing ? 'Salvando...' : 'Salvar Alterações' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin })

const props = defineProps({
  anuncio: Object,
  posicoes: Object,
  paginas: Object,
  tipos: Object,
})

const form = useForm({
  nome: props.anuncio.nome,
  posicao: props.anuncio.posicao,
  pagina: props.anuncio.pagina,
  tipo: props.anuncio.tipo,
  imagem: null,
  html_content: props.anuncio.html_content,
  script_content: props.anuncio.script_content,
  link: props.anuncio.link,
  nova_aba: props.anuncio.nova_aba,
  dimensoes: props.anuncio.dimensoes,
  largura: props.anuncio.largura,
  altura: props.anuncio.altura,
  ativo: props.anuncio.ativo,
  ordem: props.anuncio.ordem,
  data_inicio: props.anuncio.data_inicio ? new Date(props.anuncio.data_inicio).toISOString().slice(0, 16) : '',
  data_fim: props.anuncio.data_fim ? new Date(props.anuncio.data_fim).toISOString().slice(0, 16) : '',
  observacoes: props.anuncio.observacoes
})

const processing = ref(false)

const handleImageUpload = (event) => {
  form.imagem = event.target.files[0]
}

const limparCamposTipo = () => {
  if (form.tipo !== 'imagem') {
    form.imagem = null
    form.link = ''
    form.nova_aba = false
  }
  if (form.tipo !== 'html') {
    form.html_content = ''
  }
  if (form.tipo !== 'script') {
    form.script_content = ''
  }
}

const submit = () => {
  processing.value = true
  
  form.put(route('admin.anuncios.update', props.anuncio.id), {
    onFinish: () => {
      processing.value = false
    }
  })
}
</script>
