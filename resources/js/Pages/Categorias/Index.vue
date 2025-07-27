<script setup>
import { ref, computed } from 'vue'
import Principal from '@/Layouts/Principal.vue'

defineOptions({ layout: Principal });

// Props para tornar o componente reutilizável
const props = defineProps({
  modelName: {
    type: String,
    default: 'Categorias'
  },
  modelNameSingle: {
    type: String,
    default: 'Categoria'
  },
  apiEndpoint: {
    type: String,
    default: '/api/categories'
  }
})

// Estados
const loading = ref(false)
const showModal = ref(false)
const editingItem = ref(null)
const searchTerm = ref('')
const selectedItems = ref([])
const showDeleteConfirm = ref(false)
const itemToDelete = ref(null)

// Formulário
const form = ref({
  id: null,
  name: '',
  slug: '',
  description: '',
  color: '#3B82F6',
  is_active: true
})

// Dados mockados - substituir pela chamada da API
const items = ref([
  {
    id: 1,
    name: 'Política',
    slug: 'politica',
    description: 'Notícias sobre política nacional e internacional',
    color: '#1E40AF',
    is_active: true,
    posts_count: 45,
    created_at: '2024-01-15T10:30:00Z',
    updated_at: '2024-06-20T14:20:00Z'
  },
  {
    id: 2,
    name: 'Esporte',
    slug: 'esporte',
    description: 'Cobertura esportiva completa',
    color: '#059669',
    is_active: true,
    posts_count: 32,
    created_at: '2024-01-10T09:15:00Z',
    updated_at: '2024-06-18T16:45:00Z'
  },
  {
    id: 3,
    name: 'Economia',
    slug: 'economia',
    description: 'Análises econômicas e mercado financeiro',
    color: '#DC2626',
    is_active: true,
    posts_count: 28,
    created_at: '2024-01-20T11:00:00Z',
    updated_at: '2024-06-22T09:30:00Z'
  },
  {
    id: 4,
    name: 'Tecnologia',
    slug: 'tecnologia',
    description: 'Inovações e tendências tecnológicas',
    color: '#7C3AED',
    is_active: false,
    posts_count: 15,
    created_at: '2024-02-01T14:20:00Z',
    updated_at: '2024-06-10T12:15:00Z'
  },
  {
    id: 5,
    name: 'Saúde',
    slug: 'saude',
    description: 'Informações sobre saúde e bem-estar',
    color: '#10B981',
    is_active: true,
    posts_count: 22,
    created_at: '2024-01-25T08:45:00Z',
    updated_at: '2024-06-19T15:20:00Z'
  }
])

// Computed
const filteredItems = computed(() => {
  return items.value.filter(item => 
    item.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
    item.description.toLowerCase().includes(searchTerm.value.toLowerCase())
  )
})

const isEditing = computed(() => editingItem.value !== null)

// Métodos
const openModal = (item = null) => {
  editingItem.value = item
  if (item) {
    form.value = { ...item }
  } else {
    resetForm()
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingItem.value = null
  resetForm()
}

const resetForm = () => {
  form.value = {
    id: null,
    name: '',
    slug: '',
    description: '',
    color: '#3B82F6',
    is_active: true
  }
}

const generateSlug = () => {
  if (form.value.name) {
    form.value.slug = form.value.name
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .trim('-')
  }
}

const saveItem = async () => {
  loading.value = true
  
  try {
    // Simular chamada da API
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    if (isEditing.value) {
      const index = items.value.findIndex(item => item.id === form.value.id)
      if (index !== -1) {
        items.value[index] = { 
          ...form.value, 
          updated_at: new Date().toISOString(),
          posts_count: items.value[index].posts_count
        }
      }
    } else {
      const newItem = {
        ...form.value,
        id: Date.now(),
        posts_count: 0,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }
      items.value.unshift(newItem)
    }
    
    closeModal()
  } catch (error) {
    console.error('Erro ao salvar:', error)
  } finally {
    loading.value = false
  }
}

const confirmDelete = (item) => {
  itemToDelete.value = item
  showDeleteConfirm.value = true
}

const deleteItem = async () => {
  loading.value = true
  
  try {
    // Simular chamada da API
    await new Promise(resolve => setTimeout(resolve, 500))
    
    const index = items.value.findIndex(item => item.id === itemToDelete.value.id)
    if (index !== -1) {
      items.value.splice(index, 1)
    }
    
    showDeleteConfirm.value = false
    itemToDelete.value = null
  } catch (error) {
    console.error('Erro ao excluir:', error)
  } finally {
    loading.value = false
  }
}

const toggleStatus = async (item) => {
  loading.value = true
  
  try {
    // Simular chamada da API
    await new Promise(resolve => setTimeout(resolve, 300))
    
    item.is_active = !item.is_active
    item.updated_at = new Date().toISOString()
  } catch (error) {
    console.error('Erro ao alterar status:', error)
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const selectAll = () => {
  if (selectedItems.value.length === filteredItems.value.length) {
    selectedItems.value = []
  } else {
    selectedItems.value = filteredItems.value.map(item => item.id)
  }
}

const bulkDelete = async () => {
  if (selectedItems.value.length === 0) return
  
  loading.value = true
  
  try {
    // Simular chamada da API
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    items.value = items.value.filter(item => !selectedItems.value.includes(item.id))
    selectedItems.value = []
  } catch (error) {
    console.error('Erro ao excluir itens:', error)
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <section class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-azul-oxford">{{ modelName }}</h1>
            <p class="text-gray-600 mt-2">Gerencie todas as {{ modelName.toLowerCase() }} do seu portal</p>
          </div>
          <button 
            @click="openModal()"
            class="bg-azul-lazuli hover:bg-azul-oxford text-white px-6 py-3 rounded-lg font-medium transition-all shadow-sm flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Nova {{ modelNameSingle }}</span>
          </button>
        </div>
      </div>
    </section>

    <!-- Filtros e Ações -->
    <section class="bg-white border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
          <!-- Busca -->
          <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <input 
              v-model="searchTerm"
              type="text" 
              placeholder="Buscar..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
            />
          </div>

          <!-- Ações em lote -->
          <div v-if="selectedItems.length > 0" class="flex items-center space-x-3">
            <span class="text-sm text-gray-600">{{ selectedItems.length }} selecionado(s)</span>
            <button 
              @click="bulkDelete"
              class="text-red-600 hover:text-red-800 text-sm font-medium"
            >
              Excluir Selecionados
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Lista -->
    <section class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div 
            v-for="item in filteredItems" 
            :key="item.id"
            class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border"
          >
            <!-- Header do Card -->
            <div class="p-4 border-b border-gray-100">
              <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3">
                  <input 
                    v-model="selectedItems"
                    :value="item.id"
                    type="checkbox"
                    class="rounded border-gray-300 text-azul-lazuli focus:ring-azul-lazuli"
                  />
                  <div 
                    class="w-4 h-4 rounded-full"
                    :style="{ backgroundColor: item.color }"
                  ></div>
                </div>
                <div class="flex items-center space-x-1">
                  <button 
                    @click="openModal(item)"
                    class="p-1 text-gray-400 hover:text-azul-lazuli transition-colors"
                    title="Editar"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button 
                    @click="confirmDelete(item)"
                    class="p-1 text-gray-400 hover:text-red-500 transition-colors"
                    title="Excluir"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Conteúdo do Card -->
            <div class="p-4">
              <div class="space-y-3">
                <div>
                  <h3 class="font-semibold text-azul-oxford text-lg">{{ item.name }}</h3>
                  <p class="text-sm text-gray-500 font-mono">{{ item.slug }}</p>
                </div>
                
                <p class="text-gray-600 text-sm line-clamp-2">{{ item.description }}</p>
                
                <div class="flex items-center justify-between pt-2">
                  <span class="text-sm text-gray-500">{{ item.posts_count }} posts</span>
                  <button 
                    @click="toggleStatus(item)"
                    class="flex items-center space-x-1 text-sm"
                    :class="item.is_active ? 'text-green-600' : 'text-gray-400'"
                  >
                    <div 
                      class="w-2 h-2 rounded-full"
                      :class="item.is_active ? 'bg-green-500' : 'bg-gray-400'"
                    ></div>
                    <span>{{ item.is_active ? 'Ativo' : 'Inativo' }}</span>
                  </button>
                </div>
                
                <div class="text-xs text-gray-400 pt-2 border-t border-gray-100">
                  Criado em {{ formatDate(item.created_at) }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Estado Vazio -->
        <div v-if="filteredItems.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma {{ modelNameSingle.toLowerCase() }} encontrada</h3>
          <p class="mt-1 text-sm text-gray-500">Comece criando uma nova {{ modelNameSingle.toLowerCase() }}.</p>
          <div class="mt-6">
            <button 
              @click="openModal()"
              class="bg-azul-lazuli hover:bg-azul-oxford text-white px-4 py-2 rounded-lg font-medium transition-all"
            >
              Nova {{ modelNameSingle }}
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal de Criação/Edição -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-azul-oxford">
              {{ isEditing ? `Editar ${modelNameSingle}` : `Nova ${modelNameSingle}` }}
            </h3>
            <button 
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <form @submit.prevent="saveItem" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
              <input 
                v-model="form.name"
                @input="generateSlug"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                placeholder="Digite o nome"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
              <input 
                v-model="form.slug"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli font-mono text-sm"
                placeholder="slug-automatico"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
              <textarea 
                v-model="form.description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                placeholder="Descreva a categoria"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
              <input 
                v-model="form.color"
                type="color"
                class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
              />
            </div>

            <div class="flex items-center">
              <input 
                v-model="form.is_active"
                type="checkbox"
                id="is_active"
                class="rounded border-gray-300 text-azul-lazuli focus:ring-azul-lazuli"
              />
              <label for="is_active" class="ml-2 text-sm text-gray-700">Ativo</label>
            </div>

            <div class="flex space-x-3 pt-4">
              <button 
                type="button"
                @click="closeModal"
                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-all"
              >
                Cancelar
              </button>
              <button 
                type="submit"
                :disabled="loading"
                class="flex-1 bg-azul-lazuli hover:bg-azul-oxford text-white py-2 px-4 rounded-lg font-medium transition-all disabled:opacity-50"
              >
                {{ loading ? 'Salvando...' : 'Salvar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
            </div>
          </div>
          
          <p class="text-sm text-gray-500 mb-6">
            Tem certeza que deseja excluir "{{ itemToDelete?.name }}"? Esta ação não pode ser desfeita.
          </p>

          <div class="flex space-x-3">
            <button 
              @click="showDeleteConfirm = false; itemToDelete = null"
              class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-all"
            >
              Cancelar
            </button>
            <button 
              @click="deleteItem"
              :disabled="loading"
              class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-all disabled:opacity-50"
            >
              {{ loading ? 'Excluindo...' : 'Excluir' }}
            </button>
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
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>