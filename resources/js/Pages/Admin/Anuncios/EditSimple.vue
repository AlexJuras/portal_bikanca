<template>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Cabeçalho -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Editar Anúncio</h2>
                            <p class="text-gray-600 mt-1">Atualize os dados do anúncio</p>
                        </div>
                        <Link
                            :href="route('admin.anuncios.index')"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors"
                        >
                            ← Voltar
                        </Link>
                    </div>

                    <!-- Mensagens de erro global -->
                    <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <h3 class="text-red-800 font-medium">Erro ao salvar:</h3>
                        <ul class="text-red-700 text-sm mt-2 space-y-1">
                            <li v-for="(error, field) in form.errors" :key="field">
                                <strong>{{ field }}:</strong> {{ error }}
                            </li>
                        </ul>
                    </div>

                    <!-- Formulário Simplificado -->
                    <form @submit.prevent="submit">
                        <div class="space-y-6">
                            <!-- Nome -->
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome do Anúncio *
                                </label>
                                <input
                                    id="nome"
                                    v-model="form.nome"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.nome }"
                                    placeholder="Ex: Banner Principal Homepage"
                                    required
                                />
                                <div v-if="form.errors.nome" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.nome }}
                                </div>
                            </div>

                            <!-- Tipo de Anúncio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Anúncio *
                                </label>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input
                                            id="tipo_imagem_url"
                                            v-model="form.tipo_fonte"
                                            type="radio"
                                            value="url"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                        />
                                        <label for="tipo_imagem_url" class="ml-2 block text-sm text-gray-700">
                                            URL da Imagem
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input
                                            id="tipo_imagem_upload"
                                            v-model="form.tipo_fonte"
                                            type="radio"
                                            value="upload"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                        />
                                        <label for="tipo_imagem_upload" class="ml-2 block text-sm text-gray-700">
                                            Upload de Imagem
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input
                                            id="tipo_adsense"
                                            v-model="form.tipo_fonte"
                                            type="radio"
                                            value="adsense"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                        />
                                        <label for="tipo_adsense" class="ml-2 block text-sm text-gray-700">
                                            Google AdSense
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Imagem Atual -->
                            <div v-if="anuncio.imagem && form.tipo_fonte !== 'adsense'" class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-700 mb-2">Imagem Atual:</p>
                                <img 
                                    :src="getImageUrl(anuncio.imagem)" 
                                    :alt="anuncio.nome"
                                    class="max-w-xs h-auto rounded border"
                                />
                            </div>

                            <!-- URL da Imagem (se selecionado) -->
                            <div v-if="form.tipo_fonte === 'url'">
                                <label for="imagem_url" class="block text-sm font-medium text-gray-700 mb-2">
                                    URL da Imagem {{ anuncio.imagem ? '(deixe vazio para manter a atual)' : '*' }}
                                </label>
                                <input
                                    id="imagem_url"
                                    v-model="form.imagem_url"
                                    type="url"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.imagem_url }"
                                    placeholder="https://exemplo.com/banner.jpg"
                                />
                                <div v-if="form.errors.imagem_url" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.imagem_url }}
                                </div>
                                <p class="text-gray-500 text-sm mt-1">
                                    Cole aqui a URL da imagem do anúncio
                                </p>
                            </div>

                            <!-- Upload de Imagem (se selecionado) -->
                            <div v-if="form.tipo_fonte === 'upload'">
                                <label for="imagem_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Arquivo de Imagem {{ anuncio.imagem ? '(deixe vazio para manter a atual)' : '*' }}
                                </label>
                                <input
                                    id="imagem_file"
                                    @change="handleImageUpload"
                                    type="file"
                                    accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.imagem_file }"
                                />
                                <div v-if="form.errors.imagem_file" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.imagem_file }}
                                </div>
                                <p class="text-gray-500 text-sm mt-1">
                                    Formatos aceitos: JPEG, PNG, JPG, GIF, WebP. Tamanho máximo: 2MB
                                </p>
                            </div>

                            <!-- Código AdSense (se selecionado) -->
                            <div v-if="form.tipo_fonte === 'adsense'">
                                <label for="adsense_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Código do Google AdSense *
                                </label>
                                <textarea
                                    id="adsense_code"
                                    v-model="form.adsense_code"
                                    rows="6"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.adsense_code }"
                                    placeholder="Cole aqui o código do Google AdSense"
                                    :required="form.tipo_fonte === 'adsense'"
                                ></textarea>
                                <div v-if="form.errors.adsense_code" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.adsense_code }}
                                </div>
                            </div>

                            <!-- Link de Destino -->
                            <div v-if="form.tipo_fonte !== 'adsense'">
                                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">
                                    Link de Destino
                                </label>
                                <input
                                    id="link"
                                    v-model="form.link"
                                    type="url"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.link }"
                                    placeholder="https://exemplo.com/destino"
                                />
                                <div v-if="form.errors.link" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.link }}
                                </div>
                            </div>

                            <!-- Opções -->
                            <div class="space-y-3">
                                <div v-if="form.tipo_fonte !== 'adsense'" class="flex items-center">
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

                                <div class="flex items-center">
                                    <input
                                        id="ativo"
                                        v-model="form.ativo"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <label for="ativo" class="ml-2 block text-sm text-gray-700">
                                        Anúncio ativo
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input
                                        id="ativo_global"
                                        v-model="form.ativo_global"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <label for="ativo_global" class="ml-2 block text-sm text-gray-700">
                                        Disponível para uso em páginas
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                            <Link
                                :href="route('admin.anuncios.index')"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors disabled:opacity-50"
                            >
                                <span v-if="form.processing">Salvando...</span>
                                <span v-else>Atualizar Anúncio</span>
                            </button>
                        </div>
                    </form>

                    <!-- Preview -->
                    <div v-if="showPreview" class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Preview:</h3>
                        
                        <!-- Preview Imagem URL -->
                        <div v-if="form.tipo_fonte === 'url' && form.imagem_url">
                            <img 
                                :src="form.imagem_url" 
                                :alt="form.nome || 'Preview do anúncio'"
                                class="max-w-full h-auto rounded border"
                            />
                        </div>

                        <!-- Preview Upload -->
                        <div v-if="form.tipo_fonte === 'upload' && imagePreview">
                            <img 
                                :src="imagePreview" 
                                :alt="form.nome || 'Preview do anúncio'"
                                class="max-w-full h-auto rounded border"
                            />
                        </div>

                        <!-- Preview AdSense -->
                        <div v-if="form.tipo_fonte === 'adsense' && form.adsense_code">
                            <div class="bg-white border-2 border-dashed border-gray-300 rounded p-4 text-center">
                                <div class="text-4xl mb-2">📱</div>
                                <p class="text-gray-600 font-medium">Google AdSense</p>
                                <p class="text-gray-500 text-sm">{{ form.nome || 'Anúncio AdSense' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin })

const props = defineProps({
    anuncio: Object
})

const imagePreview = ref(null)

const form = useForm({
    nome: props.anuncio.nome,
    tipo: props.anuncio.tipo,
    tipo_fonte: props.anuncio.tipo_fonte || 'url',
    imagem_url: props.anuncio.imagem_url || '',
    imagem_file: null,
    adsense_code: props.anuncio.script_content || '',
    link: props.anuncio.link || '',
    nova_aba: props.anuncio.nova_aba ?? true,
    ativo: props.anuncio.ativo ?? true,
    ativo_global: props.anuncio.ativo_global ?? true,
    _method: 'PUT'
})

const showPreview = computed(() => {
    return (form.tipo_fonte === 'url' && form.imagem_url) ||
           (form.tipo_fonte === 'upload' && imagePreview.value) ||
           (form.tipo_fonte === 'adsense' && form.adsense_code)
})

const getImageUrl = (imagePath) => {
    if (!imagePath) return ''
    if (imagePath.startsWith('http')) return imagePath
    return `/storage/${imagePath}`
}

const handleImageUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.imagem_file = file
        
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

const submit = () => {
    // Determinar o tipo baseado na fonte selecionada
    if (form.tipo_fonte === 'adsense') {
        form.tipo = 'script'
    } else {
        form.tipo = 'imagem'
    }
    
    form.post(route('admin.anuncios.update', props.anuncio.id), {
        forceFormData: true,
        onSuccess: () => {
            console.log('Anúncio atualizado com sucesso!')
        },
        onError: (errors) => {
            console.error('Erro ao atualizar anúncio:', errors)
        }
    })
}
</script>
