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
        <div class="bg-gray-100 p-6">
            <h1>Welcome to the Sobre Page</h1>
            <p>Eu sou o {{ user }}</p>
        </div>
    </Layout>
</template>
