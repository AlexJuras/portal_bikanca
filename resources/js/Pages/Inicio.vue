<script setup>
import { defineProps } from "vue";
import Layout from "@/Layouts/Principal.vue";

defineProps({
    user: {
        type: String,
        default: "Anônimo",
    },
});
</script>

<template>
    <Layout>
        <div>
            <h1 class="text-3xl font-bold underline bg-amber-600">
                Página Inicial
            </h1>
            <p>Eu sou o {{ user }}</p>
        </div>
    </Layout>
</template>
