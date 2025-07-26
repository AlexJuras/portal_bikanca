<script setup>
import { ref, computed, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import Principal from "@/Layouts/Principal.vue";
import NoticiaEditor from "../Editor.vue";

defineOptions({ layout: Principal });

const props = defineProps({
    categorias: {
        type: Array,
        required: true,
    },
    autores: {
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
    layout: "",
    autor_id: "",
    imagem_capa: null,
    // gallery_images: [],
    tags: [],
});

// Estados locais
const previewMode = ref(false);
const tagInput = ref("");
const featuredImagePreview = ref(null);
const galleryPreviews = ref([]);

// Refs para inputs de arquivo
const featuredImageInput = ref(null);
// const galleryImageInput = ref(null);

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

const gerarSlug = () => {
    if (form.titulo) {
        form.slug = form.titulo
            .toLowerCase()
            .replace(/[^\w-]+/g, "-")
            .replace(/-+/g, "-")
            .trim();
    }
};

// Watch for title changes
watch(
    () => form.titulo,
    (novoTitulo) => {
        if (!form.slug || form.slug === "") {
            gerarSlug();
        }
    }
);

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
    const tag = tagInput.value.trim();
    if (tag && !form.tags.includes(tag)) {
        form.tags.push(tag);
        tagInput.value = "";
    }
};

const removeTag = (index) => {
    form.tags.splice(index, 1);
};

const addTagOnEnter = (event) => {
    if (event.key === "Enter") {
        event.preventDefault();
        addTag();
    }
};

// Funções de submissão
const salvarRascunho = () => {
    form.status = "rascunho";
    form.post("/noticias", {
        forceFormData: true,
        onSuccess: () => {
            router.visit("/noticias");
        },
    });
};

const publicarNoticia = () => {
    form.status = "publicada";
    form.post("/noticias", {
        forceFormData: true,
        onSuccess: () => {
            router.visit("/noticias");
        },
    });
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
</script>

<template>
    <Head title="Criar Nova Notícia" />

    <div class="min-h-screen bg-gray-50">
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
                        v-if="form.tags.length > 0"
                        class="mt-8 pt-6 border-t border-gray-200"
                    >
                        <h4 class="text-sm font-semibold text-azul-oxford mb-2">
                            Tags:
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="tag in form.tags"
                                :key="tag"
                                class="bg-gray-100 text-azul-oxford px-3 py-1 rounded-full text-sm"
                            >
                                {{ tag }}
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
                            :class="{ 'border-red-500': form.errors.titulo }"
                        />
                        <div class="flex justify-between items-center mt-1">
                            <p
                                v-if="form.errors.titulo"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.titulo }}
                            </p>
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
                            :class="{ 'border-red-500': form.errors.resumo }"
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p
                                v-if="form.errors.resumo"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.resumo }}
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
                            :class="{ 'border-red-500': form.errors.conteudo }"
                        />

                        <p
                            v-if="form.errors.conteudo"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.conteudo }}
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
                            v-if="form.errors.imagem_capa"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.imagem_capa }}
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
                            :class="{ 'border-red-500': form.errors.categoria }"
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
                            v-if="form.errors.categoria"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.categoria }}
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
                            :class="{ 'border-red-500': form.errors.autor_id }"
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
                            v-if="form.errors.autor_id"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.autor_id }}
                        </p>
                    </div>

                    <!-- Tags -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <label
                            class="block text-sm font-medium text-azul-oxford mb-2"
                        >
                            Tags
                        </label>
                        <div class="flex space-x-2 mb-3">
                            <input
                                v-model="tagInput"
                                type="text"
                                @keypress="addTagOnEnter"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli"
                                placeholder="Digite uma tag"
                            />
                            <button
                                type="button"
                                @click="addTag"
                                class="bg-azul-lazuli hover:bg-azul-oxford text-white px-3 py-2 rounded-lg transition-all duration-200"
                            >
                                Add
                            </button>
                        </div>

                        <div
                            v-if="form.tags.length > 0"
                            class="flex flex-wrap gap-2"
                        >
                            <span
                                v-for="(tag, index) in form.tags"
                                :key="index"
                                class="bg-celeste text-azul-oxford px-3 py-1 rounded-full text-sm flex items-center space-x-2"
                            >
                                <span>{{ tag }}</span>
                                <button
                                    type="button"
                                    @click="removeTag(index)"
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
                                        ></path>
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
