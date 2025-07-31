<template>
  <div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Nova Tag</h1>
        <p class="text-gray-600">Crie uma nova tag para organizar as notícias</p>
      </div>

      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Nome -->
        <div>
          <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
            Nome da Tag *
          </label>
          <input
            id="nome"
            v-model="form.nome"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.nome }"
            placeholder="Digite o nome da tag"
            @input="generateSlug"
          />
          <p v-if="errors.nome" class="mt-1 text-sm text-red-600">
            {{ errors.nome }}
          </p>
        </div>

        <!-- Slug -->
        <div>
          <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
            Slug *
          </label>
          <input
            id="slug"
            v-model="form.slug"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.slug }"
            placeholder="slug-da-tag"
          />
          <p v-if="errors.slug" class="mt-1 text-sm text-red-600">
            {{ errors.slug }}
          </p>
          <p class="mt-1 text-sm text-gray-500">
            O slug é usado na URL e será preenchido automaticamente
          </p>
        </div>

        <!-- Botões -->
        <div class="flex items-center justify-between pt-4">
          <Link
            :href="route('admin.tags.index')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cancelar
          </Link>

          <button
            type="submit"
            :disabled="processing"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ processing ? 'Criando...' : 'Criar Tag' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin })

const form = useForm({
  nome: '',
  slug: ''
})

const generateSlug = () => {
  if (form.nome) {
    form.slug = form.nome
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '') // Remove acentos
      .replace(/[^a-z0-9\s-]/g, '') // Remove caracteres especiais
      .replace(/\s+/g, '-') // Substitui espaços por hífens
      .replace(/-+/g, '-') // Remove hífens duplicados
      .trim()
  }
}

const submitForm = () => {
  form.post(route('admin.tags.store'), {
    onSuccess: () => {
      // Redirect será feito automaticamente pelo controller
    }
  })
}

// Computed property para acessar erros
const errors = computed(() => form.errors)
const processing = computed(() => form.processing)
</script>