<script setup>
import { ref, onMounted } from "vue";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Image from "@tiptap/extension-image";

const props = defineProps({
    modelValue: String,
});

const emit = defineEmits(["update:modelValue"]);

const editor = ref(null);

onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue,
        extensions: [StarterKit, Image],
        onUpdate({ editor }) {
            emit("update:modelValue", editor.getHTML());
        },
    });
});
</script>

<template>
    <div class="border rounded-lg p-2">
        <EditorContent :editor="editor" />
    </div>
</template>
