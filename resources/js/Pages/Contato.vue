<script setup>
import Layout from "@/Layouts/Principal.vue";

defineProps({
    contato: {
        type: String,
        default: "(86) 9 9999-9999",
    },
});
</script>

<template>
    <Layout>
        <div>
            <h1 class="text-3xl font-bold underline">Página de Contato</h1>
            <p class="mt-4">Esse é o meu contato {{ contato }}.</p>
        </div>
    </Layout>
</template>
