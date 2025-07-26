<script setup>
const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div class="flex items-center justify-center gap-2">
        <!-- Pagination Links -->
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                class="px-4 py-2 text-sm text-gray-500 bg-gray-100 rounded-lg"
                v-html="link.label"
            />
            
            <Link
                v-else
                :href="link.url"
                class="px-4 py-2 text-sm rounded-lg transition-colors"
                :class="{
                    'bg-azul-lazuli text-white': link.active,
                    'bg-gray-100 text-gray-700 hover:bg-gray-200': !link.active
                }"
                v-html="link.label"
            />
        </template>
    </div>
</template>