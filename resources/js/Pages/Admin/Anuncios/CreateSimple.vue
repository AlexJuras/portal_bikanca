<template>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Cabeçalho -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Criar Novo Anúncio</h2>
                            <p class="text-gray-600 mt-1">Preencha os dados básicos do anúncio</p>
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

                            <!-- URL da Imagem (se selecionado) -->
                            <div v-if="form.tipo_fonte === 'url'">
                                <label for="imagem_url" class="block text-sm font-medium text-gray-700 mb-2">
                                    URL da Imagem *
                                </label>
                                <input
                                    id="imagem_url"
                                    v-model="form.imagem_url"
                                    type="url"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.imagem_url }"
                                    placeholder="https://exemplo.com/banner.jpg"
                                    :required="form.tipo_fonte === 'url'"
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
                                    Arquivo de Imagem *
                                </label>
                                <input
                                    id="imagem_file"
                                    @change="handleImageUpload"
                                    type="file"
                                    accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.imagem_file }"
                                    :required="form.tipo_fonte === 'upload'"
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
                                    placeholder="<script async src=&quot;https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js&quot;></script>
<!-- Seu Anúncio -->
<ins class=&quot;adsbygoogle&quot;
     style=&quot;display:block&quot;
     data-ad-client=&quot;ca-pub-xxxxxxxxxx&quot;
     data-ad-slot=&quot;xxxxxxxxx&quot;
     data-ad-format=&quot;auto&quot;></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>"
                                    :required="form.tipo_fonte === 'adsense'"
                                ></textarea>
                                <div v-if="form.errors.adsense_code" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.adsense_code }}
                                </div>
                                <p class="text-gray-500 text-sm mt-1">
                                    Cole aqui o código HTML do Google AdSense
                                </p>
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
                                <p class="text-gray-500 text-sm mt-1">
                                    URL para onde o usuário será direcionado ao clicar no anúncio
                                </p>
                            </div>

                            <!-- Guia de Dimensões -->
                            <div v-if="form.tipo_fonte !== 'adsense'" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Dimensões Recomendadas
                                </h3>
                                <div class="space-y-2 text-sm text-blue-800">
                                    <div class="flex items-start gap-2">
                                        <span class="font-medium min-w-[140px]">Banner Topo:</span>
                                        <span>728x90px ou 970x90px (Mobile: 320x50px)</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="font-medium min-w-[140px]">Banner Meio:</span>
                                        <span>728x90px ou 970x250px</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="font-medium min-w-[140px]">Banner Lateral:</span>
                                        <span>300x250px ou 300x600px</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="font-medium min-w-[140px]">Quadrado:</span>
                                        <span>250x250px ou 300x300px</span>
                                    </div>
                                </div>
                                <p class="text-xs text-blue-700 mt-3 italic">
                                    💡 Dica: As imagens serão automaticamente redimensionadas para se ajustar ao espaço disponível, mantendo a proporção original.
                                </p>
                            </div>

                            <!-- Opções Simples -->
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
                                <span v-else>Criar Anúncio</span>
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
                                @error="previewError = true"
                                @load="previewError = false"
                            />
                            <p v-if="previewError" class="text-red-500 text-sm mt-2">
                                Erro ao carregar a imagem. Verifique se a URL está correta.
                            </p>
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
                                <p class="text-xs text-gray-400 mt-2">
                                    Preview não disponível - código será executado no site
                                </p>
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

const previewError = ref(false)
const imagePreview = ref(null)

const form = useForm({
    nome: '',
    tipo: 'imagem',
    tipo_fonte: 'url', // 'url', 'upload', 'adsense'
    imagem_url: '',
    imagem_file: null,
    adsense_code: '',
    link: '',
    nova_aba: true,
    ativo: true,
    ativo_global: true
})

const showPreview = computed(() => {
    return (form.tipo_fonte === 'url' && form.imagem_url) ||
           (form.tipo_fonte === 'upload' && imagePreview.value) ||
           (form.tipo_fonte === 'adsense' && form.adsense_code)
})

const handleImageUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.imagem_file = file
        
        // Criar preview da imagem
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

const submit = () => {
    console.log('Enviando formulário:', form.data())
    
    // Determinar o tipo baseado na fonte selecionada
    if (form.tipo_fonte === 'adsense') {
        form.tipo = 'script'
    } else {
        form.tipo = 'imagem'
    }
    
    form.post(route('admin.anuncios.store'), {
        forceFormData: true, // Necessário para upload de arquivos
        onSuccess: () => {
            console.log('Anúncio criado com sucesso!')
        },
        onError: (errors) => {
            console.error('Erro ao criar anúncio:', errors)
        }
    })
}
</script>
