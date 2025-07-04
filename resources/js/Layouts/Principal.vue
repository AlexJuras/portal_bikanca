<script setup>
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <div>
        <header class="bg-[#001f3eff] text-white p-4">
            <nav class="flex justify-between items-center">
                <div>
                    <Link href="/"> Início </Link>
                    <Link href="/sobre"> Sobre </Link>
                    <Link href="/contato"> Contato </Link>
                </div>
            </nav>
        </header>

        <main class="p-6 bg-gray-100">
            <slot/>
        </main>
    </div>
</template>