<script setup>
import { ref, computed, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import Admin from "@/Layouts/Admin.vue";
import NoticiaEditor from "@/Components/Editor.vue";

defineOptions({ layout: Admin });

const props = defineProps({
    categorias: {
        type: Array,
        required: true,
    },
    autores: {
        type: Array,
        required: true,
    },
    tags: {
        type: Array,
        required: true,
    },
});

// Form data usando Inertia
const form = useForm({
    titulo: "",
    slug: "",
    resumo: "",
    conteudo: "Digite o conteúdo da notícia aqui...",
    status: "rascunho",
    categoria_id: "",
    publicada_em: "",
    layout: "padrao", // Valor padrão para evitar undefined
    autor_id: "",
    imagem_capa: null,
    // gallery_images: [],
    tag_ids: [],
});

// Estados locais
const previewMode = ref(false);
const tagInput = ref("");
const featuredImagePreview = ref(null);
const galleryPreviews = ref([]);

// Refs para inputs de arquivo
const featuredImageInput = ref(null);
// const galleryImageInput = ref(null);

// Computed para campos pendentes
const camposPendentes = computed(() => {
    const pendentes = [];
    if (!form.titulo || form.titulo.trim() === '') {
        pendentes.push('Título');
    }
    if (!form.resumo || form.resumo.trim() === '') {
        pendentes.push('Resumo');
    }
    if (!form.conteudo || form.conteudo.trim() === '' || form.conteudo === 'Digite o conteúdo da notícia aqui...') {
        pendentes.push('Conteúdo');
    }
    if (!form.categoria_id) {
        pendentes.push('Categoria');
    }
    if (!form.autor_id) {
        pendentes.push('Autor');
    }
    return pendentes;
});

// Computed properties
const tituloLength = computed(() => form.titulo.length);
const resumoLength = computed(() => form.resumo.length);
const autorSelecionado = computed(() => {
    return (
        props.autores.find((autor) => autor.id === form.autor_id)?.nome ||
        "Autor desconhecido"
    );
});

// Funções para manipular imagens
const handleFeaturedImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.imagem_capa = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            featuredImagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeFeaturedImage = () => {
    form.imagem_capa = null;
    featuredImagePreview.value = null;
    if (featuredImageInput.value) {
        featuredImageInput.value.value = "";
    }
};

const gerarSlug = async () => {
    if (!form.titulo) return;
    
    try {
        let baseSlug = form.titulo
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // Remove acentos
            .replace(/[^\w\s-]/g, '') // Remove caracteres especiais
            .replace(/\s+/g, '-') // Substitui espaços por hífens
            .replace(/-+/g, '-') // Remove hífens duplicados
            .trim()
            .replace(/^-+|-+$/g, ''); // Remove hífens do início e fim
        
        if (!baseSlug) {
            baseSlug = 'noticia';
        }
        
        // Adiciona timestamp para garantir unicidade
        const timestamp = new Date().getTime().toString().slice(-6);
        form.slug = `${baseSlug}-${timestamp}`;
        
    } catch (error) {
        console.error('Erro ao gerar slug:', error);
        // Garante que sempre teremos uma slug válida
        form.slug = `noticia-${new Date().getTime().toString().slice(-6)}`;
    }
};

// Função para limpar erros de validação específicos
const limparErroValidacao = (campo) => {
    if (validationErrors.value[campo]) {
        delete validationErrors.value[campo];
        validationErrors.value = { ...validationErrors.value };
    }
    if (showValidationAlert.value && Object.keys(validationErrors.value).length === 0) {
        showValidationAlert.value = false;
    }
};

// Watch for title changes
watch(
    () => form.titulo,
    async (novoTitulo) => {
        limparErroValidacao('titulo');
        if (!form.slug || form.slug === "") {
            await gerarSlug();
        }
    }
);

// Watch para outros campos
watch(() => form.resumo, () => limparErroValidacao('resumo'));
watch(() => form.conteudo, () => limparErroValidacao('conteudo'));
watch(() => form.categoria_id, () => limparErroValidacao('categoria_id'));
watch(() => form.autor_id, () => limparErroValidacao('autor_id'));

watch(tagInput, (newValue) => {
    if (newValue !== "") {
        addTag();
    }
});

// const handleGalleryImageUpload = (event) => {
//     const file = event.target.files[0];
//     if (file) {
//         form.gallery_images.push(file);

//         const reader = new FileReader();
//         reader.onload = (e) => {
//             galleryPreviews.value.push(e.target.result);
//         };
//         reader.readAsDataURL(file);
//     }

//     // Reset input
//     if (galleryImageInput.value) {
//         galleryImageInput.value.value = "";
//     }
// };

// const removeGalleryImage = (index) => {
//     form.gallery_images.splice(index, 1);
//     galleryPreviews.value.splice(index, 1);
// };

// Funções para tags
const addTag = () => {
    const selectedTag = props.tags.find((tag) => tag.id === tagInput.value);
    if (selectedTag && !form.tag_ids.includes(selectedTag.id)) {
        form.tag_ids.push(selectedTag.id);
        tagInput.value = ""; // Limpar após adicionar
    }
};

const removeTag = (index) => {
    form.tag_ids.splice(index, 1);
};

const addTagOnEnter = (event) => {
    if (event.key === "Enter") {
        event.preventDefault();
        addTag();
    }
};

// Estados para controle de validação
const validationErrors = ref({});
const showValidationAlert = ref(false);

// Função para validar campos obrigatórios
const validarCamposObrigatorios = () => {
    const errors = {};
    
    if (!form.titulo || form.titulo.trim() === '') {
        errors.titulo = 'O título é obrigatório';
    }
    
    if (!form.resumo || form.resumo.trim() === '') {
        errors.resumo = 'O resumo é obrigatório';
    }
    
    if (!form.conteudo || form.conteudo.trim() === '' || form.conteudo === 'Digite o conteúdo da notícia aqui...') {
        errors.conteudo = 'O conteúdo da notícia é obrigatório';
    }
    
    if (!form.categoria_id) {
        errors.categoria_id = 'A categoria é obrigatória';
    }
    
    if (!form.autor_id) {
        errors.autor_id = 'O autor é obrigatório';
    }
    
    validationErrors.value = errors;
    return Object.keys(errors).length === 0;
};

// Função para exibir alerta de campos obrigatórios
const mostrarAlertaValidacao = () => {
    showValidationAlert.value = true;
    setTimeout(() => {
        showValidationAlert.value = false;
    }, 5000);
};

// Funções de submissão
const processarSubmissao = async (status) => {
    try {
        // Validar campos obrigatórios
        if (!validarCamposObrigatorios()) {
            mostrarAlertaValidacao();
            return;
        }

        // Garante que temos uma slug válida
        if (!form.slug) {
            await gerarSlug();
        }

        // Criar FormData manualmente para garantir envio correto
        const formData = new FormData();
        Object.keys(form.data()).forEach(key => {
            if (form.data()[key] !== null && form.data()[key] !== undefined) {
                if (key === 'tag_ids' && Array.isArray(form.data()[key])) {
                    form.data()[key].forEach((tagId, index) => {
                        formData.append(`tag_ids[${index}]`, tagId);
                    });
                } else {
                    formData.append(key, form.data()[key]);
                }
            }
        });
        formData.append('status', status);

        router.post(route("admin.noticias.store"), formData, {
            onStart: () => form.processing = true,
            onFinish: () => form.processing = false,
            onSuccess: () => {
                router.visit(route("admin.noticias.index"));
            },
            onError: (errors) => {
                console.error(`Erro ao ${status === 'rascunho' ? 'salvar rascunho' : 'publicar notícia'}:`, errors);
                form.errors = errors;
                if (errors) {
                    validationErrors.value = { ...validationErrors.value, ...errors };
                }
            },
        });
    } catch (error) {
        console.error('Erro ao processar submissão:', error);
    }
};

const salvarRascunho = () => {
    processarSubmissao("rascunho");
};

const publicarNoticia = () => {
    processarSubmissao("publicada");
};

// Função para preview
const togglePreview = () => {
    previewMode.value = !previewMode.value;
};

// Formatação de data para o preview
const formatDate = (dateString) => {
    if (!dateString) return "";
    return new Date(dateString).toLocaleDateString("pt-BR", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

// Função para traduzir mensagens de erro
const traduzirErro = (mensagem) => {
    const traducoes = {
        'The titulo field is required.': 'O campo título é obrigatório.',
        'The resumo field is required.': 'O campo resumo é obrigatório.',
        'The conteudo field is required.': 'O campo conteúdo é obrigatório.',
        'The categoria_id field is required.': 'O campo categoria é obrigatório.',
        'The autor_id field is required.': 'O campo autor é obrigatório.',
        'The slug field is required.': 'O campo slug é obrigatório.',
        'The imagem_capa field must be an image.': 'A imagem de capa deve ser uma imagem válida.',
        'The imagem_capa field must not be greater than 2048 kilobytes.': 'A imagem de capa não pode ser maior que 2MB.',
        'validation.required': 'Este campo é obrigatório.',
        'validation.string': 'Este campo deve ser um texto.',
        'validation.max.string': 'Este campo não pode ter mais que :max caracteres.',
        'validation.image': 'Este arquivo deve ser uma imagem.',
        'validation.mimes': 'Este arquivo deve ser do tipo: :values.',
        'validation.uploaded': 'Falha no upload do arquivo.',
        'validation.max.file': 'O arquivo não pode ser maior que :max KB.',
    };
    
    return traducoes[mensagem] || mensagem;
};

// Computed para erros traduzidos
const errosTraduzidos = computed(() => {
    const erros = {};
    Object.keys(form.errors).forEach(campo => {
        erros[campo] = traduzirErro(form.errors[campo]);
    });
    return erros;
});
</script>

<template>
    <Head title="Criar Nova Notícia" />

    <div class="min-h-screen bg-gray-50">
        <!-- Alerta de Validação -->
        <div 
            v-if="showValidationAlert" 
            class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 animate-pulse"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                </path>
            </svg>
            <div>
                <p class="font-medium">Campos obrigatórios não preenchidos!</p>
                <p class="text-sm opacity-90">Verifique os campos marcados abaixo e preencha todos os dados obrigatórios.</p>
            </div>
            <button 
                @click="showValidationAlert = false"
                class="ml-4 text-white hover:text-gray-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Header -->
        <section class="bg-white shadow-sm border-b-2 border-azul-lazuli">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-azul-oxford">
                        Criar Nova Notícia
                    </h1>
                    <div class="flex space-x-3">
                        <button
                            @click="togglePreview"
                            class="flex items-center space-x-2 bg-cinza hover:bg-azul-oxford text-white px-4 py-2 rounded-lg transition-all duration-200"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                ></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                ></path>
                            </svg>
                            <span>{{
                                previewMode ? "Editar" : "Preview"
                            }}</span>
                        </button>
                        <button
                            @click="salvarRascunho"
                            :disabled="form.processing"
                            class="flex items-center space-x-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-all duration-200 disabled:opacity-50"
                            :class="{ 'animate-pulse': Object.keys(validationErrors).length > 0 }"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"
                                ></path>
                            </svg>
                            <span>{{
                                form.processing
                                    ? "Salvando..."
                                    : "Salvar Rascunho"
                            }}</span>
                        </button>
                        <button
                            @click="publicarNoticia"
                            :disabled="form.processing"
                            class="flex items-center space-x-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 disabled:opacity-50"
                            :class="{ 'animate-pulse': Object.keys(validationErrors).length > 0 }"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                ></path>
                            </svg>
                            <span>{{
                                form.processing ? "Publicando..." : "Publicar"
                            }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Preview Mode -->
        <div
            v-if="previewMode"
            class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
        >
            <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img
                    v-if="featuredImagePreview"
                    :src="featuredImagePreview"
                    :alt="form.titulo"
                    class="w-full h-96 object-cover"
                />

                <div class="p-8">
                    <div class="flex items-center space-x-4 mb-4">
                        <span
                            v-if="form.categoria"
                            class="bg-azul-lazuli text-white px-3 py-1 rounded-full text-sm font-medium"
                        >
                            {{ form.categoria }}
                        </span>
                        <span
                            v-if="form.publicada_em"
                            class="text-cinza text-sm"
                        >
                            {{ formatDate(form.publicada_em) }}
                        </span>
                    </div>

                    <h1
                        class="text-4xl font-bold text-azul-oxford mb-4 leading-tight"
                    >
                        {{ form.titulo || "Título da notícia" }}
                    </h1>

                    <p
                        v-if="form.resumo"
                        class="text-xl text-gray-600 mb-6 leading-relaxed"
                    >
                        {{ form.resumo }}
                    </p>

                    <div
                        class="flex items-center space-x-3 mb-8 pb-6 border-b border-gray-200"
                    >
                        <img
                            src="/public/User.png"
                            :alt="form.autor_id"
                            class="w-12 h-12 rounded-full border-2 border-azul-oxford"
                        />
                        <div>
                            <p class="font-semibold text-azul-oxford">
                                {{ autorSelecionado || "Autor" }}
                            </p>
                            <p class="text-sm text-cinza">Jornalista</p>
                        </div>
                    </div>

                    <div class="prose prose-lg max-w-none px-8">
                        <div v-html="form.conteudo" class="mb-6"></div>
                        <!-- <p
                            v-for="(paragraph, index) in form.conteudo
                                .split('\n')
                                .filter((p) => p.trim())"
                            :key="index"
                            class="mb-4 text-gray-700 leading-relaxed"
                        >
                            {{ paragraph }}
                        </p> -->
                    </div>

                    <div v-if="galleryPreviews.length > 0" class="mt-8">
                        <h3 class="text-lg font-semibold text-azul-oxford mb-4">
                            Galeria de Imagens
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <img
                                v-for="(image, index) in galleryPreviews"
                                :key="index"
                                :src="image"
                                :alt="`Imagem ${index + 1}`"
                                class="w-full h-48 object-cover rounded-lg"
                            />
                        </div>
                    </div>

                    <div
                        v-if="form.tag_ids.length > 0"
                        class="mt-8 pt-6 border-t border-gray-200"
                    >
                        <h4 class="text-sm font-semibold text-azul-oxford mb-2">
                            Tags:
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="tagId in form.tag_ids"
                                :key="tagId"
                                class="bg-gray-100 text-azul-oxford px-3 py-1 rounded-full text-sm"
                            >
                                {{
                                    props.tags.find((tag) => tag.id === tagId)
                                        ?.nome
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Edit Mode -->
        <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Conteúdo Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Título -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <label
                            class="block text-sm font-medium text-azul-oxford mb-2"
                        >
                            Título da Notícia *
                        </label>
                        <input
                            v-model="form.titulo"
                            type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli text-lg"
                            placeholder="Digite o título da notícia..."
                            maxlength="200"
                            :class="{
                                'border-red-500 bg-red-50': errosTraduzidos.titulo || validationErrors.titulo,
                                'focus:border-red-500 focus:ring-red-500': errosTraduzidos.titulo || validationErrors.titulo
                            }"
                        />
                        <div class="flex justify-between items-center mt-1">
                            <div
                                class="text-red-600 text-sm"
                                v-if="errosTraduzidos.titulo || validationErrors.titulo"
                            >
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errosTraduzidos.titulo || validationErrors.titulo }}
                            </div>
                            <p class="text-sm text-cinza">
                                {{ tituloLength }}/200 caracteres
                            </p>
                        </div>
                    </div>

                    <!-- Resumo -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <label
                            class="block text-sm font-medium text-azul-oxford mb-2"
                        >
                            Resumo/Subtítulo *
                        </label>
                        <textarea
                            v-model="form.resumo"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                            placeholder="Escreva um resumo da notícia..."
                            maxlength="300"
                            :class="{ 
                                'border-red-500 bg-red-50': errosTraduzidos.resumo || validationErrors.resumo,
                                'border-green-500 bg-green-50': form.resumo && form.resumo.trim() !== ''
                            }"
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p
                                v-if="errosTraduzidos.resumo || validationErrors.resumo"
                                class="text-sm text-red-600 font-medium"
                            >
                                {{ errosTraduzidos.resumo || validationErrors.resumo }}
                            </p>
                            <p class="text-sm text-cinza">
                                {{ resumoLength }}/300 caracteres
                            </p>
                        </div>
                    </div>

                    <!-- Editor de Conteúdo -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <label
                            class="block text-sm font-medium text-azul-oxford mb-2"
                        >
                            Conteúdo da Notícia *
                        </label>

                        <NoticiaEditor
                            v-model="form.conteudo"
                            placeholder="Escreva o conteúdo da notícia..."
                            class="min-h-[200px] w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                            :class="{ 
                                'border-red-500 bg-red-50': errosTraduzidos.conteudo || validationErrors.conteudo,
                                'border-green-500 bg-green-50': form.conteudo && form.conteudo.trim() !== '' && form.conteudo !== 'Digite o conteúdo da notícia aqui...'
                            }"
                        />

                        <p
                            v-if="errosTraduzidos.conteudo || validationErrors.conteudo"
                            class="text-sm text-red-600 font-medium mt-1"
                        >
                            {{ errosTraduzidos.conteudo || validationErrors.conteudo }}
                        </p>
                    </div>

                    <!-- Galeria de Imagens -->
                    <!-- <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-azul-oxford">
                                Galeria de Imagens
                            </h3>
                            <button
                                type="button"
                                @click="galleryImageInput?.click()"
                                class="flex items-center space-x-2 bg-azul-lazuli hover:bg-azul-oxford text-white px-3 py-2 rounded-lg transition-all duration-200"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                    ></path>
                                </svg>
                                <span>Adicionar Imagem</span>
                            </button>
                        </div>

                        <input
                            ref="galleryImageInput"
                            type="file"
                            @change="handleGalleryImageUpload"
                            accept="image/*"
                            class="hidden"
                        />

                        <div
                            v-if="galleryPreviews.length > 0"
                            class="grid grid-cols-2 md:grid-cols-3 gap-4"
                        >
                            <div
                                v-for="(image, index) in galleryPreviews"
                                :key="index"
                                class="relative group"
                            >
                                <img
                                    :src="image"
                                    :alt="`Imagem ${index + 1}`"
                                    class="w-full h-24 object-cover rounded-lg"
                                />
                                <button
                                    type="button"
                                    @click="removeGalleryImage(index)"
                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 transition-all duration-200 opacity-0 group-hover:opacity-100"
                                >
                                    <svg
                                        class="w-3 h-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div> -->
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Campos Obrigatórios Pendentes -->
                    <div 
                        v-if="camposPendentes.length > 0" 
                        class="bg-red-50 border border-red-200 rounded-lg shadow-sm p-6"
                    >
                        <h3 class="text-lg font-medium text-red-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                            Campos Obrigatórios
                        </h3>
                        <p class="text-sm text-red-700 mb-3">
                            Os seguintes campos são obrigatórios para salvar a notícia:
                        </p>
                        <ul class="space-y-2">
                            <li 
                                v-for="campo in camposPendentes" 
                                :key="campo"
                                class="flex items-center text-sm text-red-700"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ campo }}
                            </li>
                        </ul>
                    </div>

                    <!-- Campos Preenchidos -->
                    <div 
                        v-if="camposPendentes.length < 5" 
                        class="bg-green-50 border border-green-200 rounded-lg shadow-sm p-6"
                    >
                        <h3 class="text-lg font-medium text-green-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Progresso
                        </h3>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-green-700">{{ 5 - camposPendentes.length }} de 5 campos preenchidos</span>
                            <span class="text-sm font-medium text-green-800">{{ Math.round((5 - camposPendentes.length) / 5 * 100) }}%</span>
                        </div>
                        <div class="w-full bg-green-200 rounded-full h-2">
                            <div 
                                class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                                :style="{ width: `${(5 - camposPendentes.length) / 5 * 100}%` }"
                            ></div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-azul-oxford mb-4">
                            Status
                        </h3>
                        <select
                            v-model="form.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                        >
                            <option value="rascunho">Rascunho</option>
                            <option value="publicada">Publicada</option>
                            <option value="arquivada">Arquivada</option>
                            <option value="agendada">Agendada</option>
                        </select>
                    </div>

                    <!-- Imagem Principal -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-azul-oxford mb-4">
                            Imagem Principal
                        </h3>

                        <div v-if="featuredImagePreview" class="relative mb-4">
                            <img
                                :src="featuredImagePreview"
                                alt="Imagem principal"
                                class="w-full h-48 object-cover rounded-lg"
                            />
                            <button
                                type="button"
                                @click="removeFeaturedImage"
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transition-all duration-200"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    ></path>
                                </svg>
                            </button>
                        </div>

                        <button
                            v-else
                            type="button"
                            @click="featuredImageInput?.click()"
                            class="w-full h-48 border-2 border-dashed border-gray-300 hover:border-azul-lazuli rounded-lg flex flex-col items-center justify-center transition-all duration-200"
                        >
                            <svg
                                class="w-8 h-8 text-gray-400 mb-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                            <span class="text-cinza"
                                >Clique para adicionar imagem</span
                            >
                        </button>

                        <input
                            ref="featuredImageInput"
                            type="file"
                            @change="handleFeaturedImageUpload"
                            accept="image/*"
                            class="hidden"
                        />
                        <p
                            v-if="errosTraduzidos.imagem_capa"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ errosTraduzidos.imagem_capa }}
                        </p>
                    </div>

                    <!-- Categoria -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <label
                            class="block text-sm font-medium text-azul-oxford mb-2"
                        >
                            Categoria *
                        </label>
                        <select
                            v-model="form.categoria_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                            :class="{ 
                                'border-red-500 bg-red-50': errosTraduzidos.categoria_id || validationErrors.categoria_id,
                                'border-green-500 bg-green-50': form.categoria_id
                            }"
                        >
                            <option value="">Selecione uma categoria</option>
                            <option
                                v-for="categoria in categorias"
                                :key="categoria.id"
                                :value="categoria.id"
                            >
                                {{ categoria.nome }}
                            </option>
                        </select>
                        <p
                            v-if="errosTraduzidos.categoria_id || validationErrors.categoria_id"
                            class="text-sm text-red-600 font-medium mt-1"
                        >
                            {{ errosTraduzidos.categoria_id || validationErrors.categoria_id }}
                        </p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <label
                            class="block text-sm font-medium text-azul-oxford mb-2"
                        >
                            Autor *
                        </label>
                        <select
                            v-model="form.autor_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                            :class="{ 
                                'border-red-500 bg-red-50': errosTraduzidos.autor_id || validationErrors.autor_id,
                                'border-green-500 bg-green-50': form.autor_id
                            }"
                        >
                            <option value="">Selecione um(a) autor(a)</option>
                            <option
                                v-for="autor in autores"
                                :key="autor.id"
                                :value="autor.id"
                            >
                                {{ autor.nome }}
                            </option>
                        </select>
                        <p
                            v-if="errosTraduzidos.autor_id || validationErrors.autor_id"
                            class="text-sm text-red-600 font-medium mt-1"
                        >
                            {{ errosTraduzidos.autor_id || validationErrors.autor_id }}
                        </p>
                    </div>

                    <!-- Tags -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <label
                            class="block text-sm font-medium text-azul-oxford mb-2"
                        >
                            Tags
                        </label>
                        <select
                            v-model="tagInput"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                        >
                            <option value="">Selecione uma tag</option>
                            <option
                                v-for="tag in props.tags"
                                :key="tag.id"
                                :value="tag.id"
                                :disabled="form.tag_ids.includes(tag.id)"
                            >
                                {{ tag.nome }}
                            </option>
                        </select>

                        <div
                            v-if="form.tag_ids.length > 0"
                            class="flex flex-wrap gap-2"
                        >
                            <span
                                v-for="tagId in form.tag_ids"
                                :key="tagId"
                                class="bg-celeste text-azul-oxford px-3 py-1 rounded-full text-sm flex items-center space-x-2"
                            >
                                <span>{{
                                    props.tags.find((t) => t.id === tagId)?.nome
                                }}</span>
                                <button
                                    type="button"
                                    @click="
                                        () =>
                                            removeTag(
                                                form.tag_ids.indexOf(tagId)
                                            )
                                    "
                                    class="text-azul-oxford hover:text-azul-noite transition-colors"
                                >
                                    <svg
                                        class="w-3 h-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
