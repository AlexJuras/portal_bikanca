<script setup>
import { useEditor, EditorContent } from "@tiptap/vue-3";
import Placeholder from "@tiptap/extension-placeholder";
import StarterKit from "@tiptap/starter-kit";
import Underline from "@tiptap/extension-underline";
import BoldIcon from "vue-material-design-icons/FormatBold.vue";
import ItalicIcon from "vue-material-design-icons/FormatItalic.vue";
import StrikeIcon from "vue-material-design-icons/FormatStrikethrough.vue";
import SublinharIcon from "vue-material-design-icons/FormatUnderline.vue";
import ListaIcon from "vue-material-design-icons/FormatListBulleted.vue";
import ListaOrdenadaIcon from "vue-material-design-icons/FormatListNumbered.vue";
import ComentarioIcon from "vue-material-design-icons/CommentQuote.vue";
import CodigoIcon from "vue-material-design-icons/CodeString.vue";
import LinhaIcon from "vue-material-design-icons/Minus.vue";
import UndoIcon from "vue-material-design-icons/Undo.vue";
import RedoIcon from "vue-material-design-icons/Redo.vue";

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    placeholder: {
        type: String,
        default: "Escreva seu conteúdo aqui...",
    },
    error: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit,
        Underline,
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
    ],
    onUpdate: ({ editor }) => {
        emit("update:modelValue", editor.getHTML());
    },
    // editorProps: {
    //     attributes: {
    //         class: "prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none p-4 min-h-[400px] max-h-[400px] overflow-y-auto",
    //     },
    // },
});
</script>

<template>
    <div class="border rounded-lg">
        <section
            v-if="editor"
            class="buttons flex text-azul-oxford items-center flex-wrap gap-x-4 border border-gray-200 p-4"
        >
            <button
                @click="editor.chain().focus().toggleBold().run()"
                :disabled="!editor.can().chain().focus().toggleBold().run()"
                :class="{ 'bg-blue-100': editor.isActive('bold') }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <BoldIcon class="w-5 h-5" />
            </button>
            <button
                @click="editor.chain().focus().toggleItalic().run()"
                :disabled="!editor.can().chain().focus().toggleItalic().run()"
                :class="{ 'bg-blue-100': editor.isActive('italic') }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <ItalicIcon class="w-5 h-5" />
            </button>
            <button
                @click="editor.chain().focus().toggleStrike().run()"
                :disabled="!editor.can().chain().focus().toggleStrike().run()"
                :class="{ 'bg-blue-100': editor.isActive('strike') }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <StrikeIcon class="w-5 h-5" />
            </button>
            <button
                @click="editor.chain().focus().toggleUnderline().run()"
                :disabled="
                    !editor.can().chain().focus().toggleUnderline().run()
                "
                :class="{ 'bg-blue-100': editor.isActive('underline') }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <SublinharIcon class="w-5 h-5" />
            </button>
            <button
                @click="
                    editor.chain().focus().toggleHeading({ level: 1 }).run()
                "
                :class="{
                    'bg-blue-100': editor.isActive('heading', { level: 1 }),
                }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <strong>H1</strong>
            </button>
            <button
                @click="
                    editor.chain().focus().toggleHeading({ level: 2 }).run()
                "
                :class="{
                    'bg-blue-100': editor.isActive('heading', { level: 2 }),
                }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <strong>H2</strong>
            </button>
            <button
                @click="
                    editor.chain().focus().toggleHeading({ level: 3 }).run()
                "
                :class="{
                    'bg-blue-100': editor.isActive('heading', { level: 3 }),
                }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <strong>H3</strong>
            </button>
            <button
                @click="
                    editor.chain().focus().toggleHeading({ level: 4 }).run()
                "
                :class="{
                    'bg-blue-100': editor.isActive('heading', { level: 4 }),
                }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <strong>H4</strong>
            </button>
            <button
                @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ 'bg-blue-100': editor.isActive('bulletList') }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <ListaIcon class="w-5 h-5" />
            </button>
            <button
                @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ 'bg-blue-100': editor.isActive('orderedList') }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <ListaOrdenadaIcon class="w-5 h-5" />
            </button>
            <button
                @click="editor.chain().focus().toggleCodeBlock().run()"
                :class="{ 'bg-blue-100': editor.isActive('codeBlock') }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <CodigoIcon class="w-5 h-5" />
            </button>
            <button
                @click="editor.chain().focus().toggleBlockquote().run()"
                :class="{ 'bg-blue-100': editor.isActive('blockquote') }"
                class="p-1 rounded hover:bg-blue-100 transition-colors"
            >
                <ComentarioIcon class="w-5 h-5" />
            </button>
            <button @click="editor.chain().focus().setHorizontalRule().run()">
                <LinhaIcon class="w-5 h-5" />
            </button>
            <button
                @click="editor.chain().focus().undo().run()"
                :disabled="!editor.can().chain().focus().undo().run()"
                class="p-1 disabled:opacity-30 rounded hover:bg-blue-100 transition-colors"
            >
                <UndoIcon class="w-5 h-5" />
            </button>
            <button
                @click="editor.chain().focus().redo().run()"
                :disabled="!editor.can().chain().focus().redo().run()"
                class="p-1 disabled:opacity-30 rounded hover:bg-blue-100 transition-colors"
            >
                <RedoIcon class="w-5 h-5" />
            </button>
        </section>
        <EditorContent
            :editor="editor"
            class="prose prose-sm sm:prose lg:prose-lg mx-auto focus:outline-none p-4 min-h-[400px] max-h-[400px] overflow-y-auto"
        />
        <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<style>
.ProseMirror {
    > * + * {
        margin-top: 0.5em; /* Reduz o espaço entre elementos */
    }

    p {
        margin: 0.3em 0; /* Reduz o espaço entre parágrafos */
        line-height: 1.4; /* Ajusta a altura da linha */
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        margin: 0.5em 0; /* Reduz o espaço entre títulos */
        line-height: 1.2;
    }

    ul,
    ol {
        margin: 0.3em 0;
        padding: 0 1rem;
    }

    li > p {
        margin: 0; /* Remove margem entre parágrafos dentro de listas */
    }

    blockquote {
        margin: 0.5em 0;
        padding-left: 1rem;
    }

    outline: none !important; /* Remove a borda de foco */

    &:focus {
        outline: none !important;
        box-shadow: none !important;
    }
}

/* Ajusta as classes do prose do Tailwind */
.prose {
    max-width: none !important;
    line-height: 1.4 !important;
}

.prose p {
    margin: 0.3em 0 !important;
}
</style>
