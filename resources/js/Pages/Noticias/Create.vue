<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import NoticiaEditor from "./Editor.vue";
import Principal from "@/Layouts/Principal.vue";

defineOptions({ layout: Principal });

defineProps({
    autores: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    titulo: "",
    slug: "",
    conteudo: "",
    autor_id: null,
    status: "rascunho",
    layout: "padrao",
});

const submit = () => {
    form.post("/noticias", {
        onSuccess: () => {
            form.reset();
        },
    });
};

const statusOptions = [
    { value: "rascunho", label: "Rascunho" },
    { value: "publicada", label: "Publicada" },
    { value: "arquivada", label: "Arquivada" },
    { value: "agendada", label: "Agendada" },
];
</script>

<template>
    <div class="max-w-4xl mx-auto p-4">
        <form @submit.prevent="submit" class="space-y-6">
            <!-- Erros -->
            <div v-if="form.errors" class="bg-red-50 p-4 rounded">
                <div
                    v-for="(error, key) in form.errors"
                    :key="key"
                    class="text-red-600"
                >
                    {{ error }}
                </div>
            </div>

            <div class="space-y-4">
                <input
                    v-model="form.titulo"
                    type="text"
                    placeholder="Título"
                    class="w-full border p-2 rounded"
                    :class="{ 'border-red-500': form.errors.titulo }"
                />

                <input
                    v-model="form.slug"
                    type="text"
                    placeholder="Slug"
                    class="w-full border p-2 rounded"
                    :class="{ 'border-red-500': form.errors.slug }"
                />

                <select
                    v-model="form.status"
                    class="w-full border p-2 rounded"
                    :class="{ 'border-red-500': form.errors.status }"
                >
                    <option
                        v-for="option in statusOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>

                <select
                    v-model="form.autor_id"
                    class="w-full border p-2 rounded"
                    :class="{ 'border-red-500': form.errors.autor_id }"
                >
                    <option value="">Selecione um autor</option>
                    <option
                        v-for="autor in autores"
                        :key="autor.id"
                        :value="autor.id"
                    >
                        {{ autor.nome }}
                    </option>
                </select>

                <select
                    v-model="form.layout"
                    class="w-full border p-2 rounded"
                    :class="{ 'border-red-500': form.errors.layout }"
                >
                    <option value="padrao">Padrão</option>
                    <option value="destaque">Com Destaque</option>
                    <option value="imagem-grande">Imagem grande no topo</option>
                </select>

                <NoticiaEditor
                    v-model="form.conteudo"
                    class="min-h-[200px]"
                    :class="{ 'border-red-500': form.errors.conteudo }"
                />

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? "Salvando..." : "Criar Notícia" }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
