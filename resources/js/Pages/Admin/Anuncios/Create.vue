<template>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Cabeçalho -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Criar Novo Anúncio</h2>
                            <p class="text-gray-600 mt-1">Preencha os dados do anúncio</p>
                        </div>
                        <Link
                            :href="route('admin.anuncios.index')"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors"
                        >
                            ← Voltar
                        </Link>
                    </div>

                    <!-- Formulário -->
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div class="md:col-span-2">
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

                            <!-- Tipo -->
                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo *
                                </label>
                                <select
                                    id="tipo"
                                    v-model="form.tipo"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.tipo }"
                                    required
                                >
                                    <option value="">Selecione o tipo</option>
                                    <option value="imagem">Imagem</option>
                                    <option value="html">HTML</option>
                                    <option value="script">Script</option>
                                </select>
                                <div v-if="form.errors.tipo" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.tipo }}
                                </div>
                            </div>

                            <!-- Link -->
                            <div>
                                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">
                                    Link de Destino
                                </label>
                                <input
                                    id="link"
                                    v-model="form.link"
                                    type="url"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.link }"
                                    placeholder="https://exemplo.com"
                                />
                                <div v-if="form.errors.link" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.link }}
                                </div>
                            </div>

                            <!-- Upload de Imagem (apenas se tipo for imagem) -->
                            <div v-if="form.tipo === 'imagem'" class="md:col-span-2">
                                <label for="imagem" class="block text-sm font-medium text-gray-700 mb-2">
                                    Imagem do Anúncio *
                                </label>
                                <input
                                    id="imagem"
                                    @change="handleImageUpload"
                                    type="file"
                                    accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.imagem }"
                                />
                                <div v-if="form.errors.imagem" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.imagem }}
                                </div>
                                <p class="text-gray-500 text-sm mt-1">
                                    Formatos aceitos: JPEG, PNG, JPG, GIF, WebP. Tamanho máximo: 2MB
                                </p>
                            </div>

                            <!-- HTML Content (apenas se tipo for html) -->
                            <div v-if="form.tipo === 'html'" class="md:col-span-2">
                                <label for="html_content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Conteúdo HTML *
                                </label>
                                <textarea
                                    id="html_content"
                                    v-model="form.html_content"
                                    rows="8"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.html_content }"
                                    placeholder="<div>Seu código HTML aqui...</div>"
                                ></textarea>
                                <div v-if="form.errors.html_content" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.html_content }}
                                </div>
                            </div>

                            <!-- Script Content (apenas se tipo for script) -->
                            <div v-if="form.tipo === 'script'" class="md:col-span-2">
                                <label for="script_content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Código Script *
                                </label>
                                <textarea
                                    id="script_content"
                                    v-model="form.script_content"
                                    rows="8"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.script_content }"
                                    placeholder="<script>/* Seu código JavaScript aqui */</script>"
                                ></textarea>
                                <div v-if="form.errors.script_content" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.script_content }}
                                </div>
                            </div>

                            <!-- Dimensões -->
                            <div>
                                <label for="largura" class="block text-sm font-medium text-gray-700 mb-2">
                                    Largura (px)
                                </label>
                                <input
                                    id="largura"
                                    v-model="form.largura"
                                    type="number"
                                    min="1"
                                    max="2000"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.largura }"
                                    placeholder="728"
                                />
                                <div v-if="form.errors.largura" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.largura }}
                                </div>
                            </div>

                            <div>
                                <label for="altura" class="block text-sm font-medium text-gray-700 mb-2">
                                    Altura (px)
                                </label>
                                <input
                                    id="altura"
                                    v-model="form.altura"
                                    type="number"
                                    min="1"
                                    max="2000"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.altura }"
                                    placeholder="90"
                                />
                                <div v-if="form.errors.altura" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.altura }}
                                </div>
                            </div>

                            <!-- Datas -->
                            <div>
                                <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                    Data de Início
                                </label>
                                <input
                                    id="data_inicio"
                                    v-model="form.data_inicio"
                                    type="datetime-local"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.data_inicio }"
                                />
                                <div v-if="form.errors.data_inicio" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.data_inicio }}
                                </div>
                            </div>

                            <div>
                                <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">
                                    Data de Fim
                                </label>
                                <input
                                    id="data_fim"
                                    v-model="form.data_fim"
                                    type="datetime-local"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.data_fim }"
                                />
                                <div v-if="form.errors.data_fim" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.data_fim }}
                                </div>
                            </div>

                            <!-- Opções -->
                            <div class="md:col-span-2">
                                <div class="space-y-4">
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
                                            Globalmente ativo (pode ser usado em páginas)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="md:col-span-2">
                                <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observações
                                </label>
                                <textarea
                                    id="observacoes"
                                    v-model="form.observacoes"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.observacoes }"
                                    placeholder="Observações internas sobre este anúncio..."
                                ></textarea>
                                <div v-if="form.errors.observacoes" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.observacoes }}
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
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin })

const props = defineProps({
    tipos: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    nome: '',
    tipo: 'imagem',
    imagem: null,
    html_content: '',
    script_content: '',
    link: '',
    nova_aba: true,
    dimensoes: '',
    largura: null,
    altura: null,
    ativo: true,
    ativo_global: true,
    data_inicio: '',
    data_fim: '',
    observacoes: ''
})

const handleImageUpload = (event) => {
    form.imagem = event.target.files[0]
}

const submit = () => {
    form.post(route('admin.anuncios.store'), {
        forceFormData: true,
    })
}
</script>