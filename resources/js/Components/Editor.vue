<script setup>
import { useEditor, EditorContent } from "@tiptap/vue-3";
import { ref, useAttrs } from "vue";
import Placeholder from "@tiptap/extension-placeholder";
import StarterKit from "@tiptap/starter-kit";
import Underline from "@tiptap/extension-underline";
import Image from "@tiptap/extension-image";
import Link from "@tiptap/extension-link";
import TextAlign from "@tiptap/extension-text-align";
import { TextStyle } from "@tiptap/extension-text-style";
import { FontFamily } from "@tiptap/extension-font-family";
import { Color } from "@tiptap/extension-color";
import Highlight from "@tiptap/extension-highlight";
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
import ImageIcon from "vue-material-design-icons/Image.vue";
import LinkIcon from "vue-material-design-icons/Link.vue";
import AlignLeftIcon from "vue-material-design-icons/FormatAlignLeft.vue";
import AlignCenterIcon from "vue-material-design-icons/FormatAlignCenter.vue";
import AlignRightIcon from "vue-material-design-icons/FormatAlignRight.vue";
import AlignJustifyIcon from "vue-material-design-icons/FormatAlignJustify.vue";
import HighlightIcon from "vue-material-design-icons/Marker.vue";

// Desabilitar herança automática de atributos
defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    placeholder: {
        type: String,
        default: "Digite o conteúdo da notícia aqui...",
    },
    error: {
        type: String,
        default: "",
    },
});

const attrs = useAttrs();
const emit = defineEmits(["update:modelValue"]);

// Estados para modais
const showImageModal = ref(false);
const showLinkModal = ref(false);
const imageUrl = ref('');
const imageAlt = ref('');
const linkUrl = ref('');
const linkText = ref('');

// Estados para seleção de fonte
const showFontDropdown = ref(false);
const availableFonts = [
    { name: 'Arial', value: 'Arial, sans-serif' },
    { name: 'Helvetica', value: 'Helvetica, sans-serif' },
    { name: 'Times New Roman', value: 'Times New Roman, serif' },
    { name: 'Georgia', value: 'Georgia, serif' },
    { name: 'Verdana', value: 'Verdana, sans-serif' },
    { name: 'Tahoma', value: 'Tahoma, sans-serif' },
    { name: 'Trebuchet MS', value: 'Trebuchet MS, sans-serif' },
    { name: 'Impact', value: 'Impact, sans-serif' },
    { name: 'Courier New', value: 'Courier New, monospace' },
    { name: 'Lucida Console', value: 'Lucida Console, monospace' },
    { name: 'Comic Sans MS', value: 'Comic Sans MS, cursive' },
    { name: 'Roboto', value: 'Roboto, sans-serif' },
    { name: 'Open Sans', value: 'Open Sans, sans-serif' },
    { name: 'Lato', value: 'Lato, sans-serif' },
    { name: 'Montserrat', value: 'Montserrat, sans-serif' }
];

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            // Desabilitar link do StarterKit se estiver incluído
            link: false,
        }),
        Underline,
        Image.configure({
            inline: true,
            allowBase64: true,
            HTMLAttributes: {
                class: 'max-w-full h-auto rounded-lg my-4',
            },
        }),
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                class: 'text-blue-600 hover:text-blue-800 underline',
            },
        }),
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
        TextStyle,
        FontFamily.configure({
            types: ['textStyle'],
        }),
        Color,
        Highlight.configure({
            HTMLAttributes: {
                class: 'bg-yellow-200 px-1 rounded',
            },
        }),
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
    ],
    onUpdate: ({ editor }) => {
        emit("update:modelValue", editor.getHTML());
    },
});

// Funções para inserir imagem
const openImageModal = () => {
    showImageModal.value = true;
    imageUrl.value = '';
    imageAlt.value = '';
};

const insertImage = () => {
    if (imageUrl.value) {
        editor.value.chain().focus().setImage({ 
            src: imageUrl.value, 
            alt: imageAlt.value || 'Imagem' 
        }).run();
        showImageModal.value = false;
    }
};

// Funções para inserir link
const openLinkModal = () => {
    const { from, to } = editor.value.state.selection;
    const selectedText = editor.value.state.doc.textBetween(from, to);
    
    showLinkModal.value = true;
    linkText.value = selectedText;
    linkUrl.value = '';
};

const insertLink = () => {
    if (linkUrl.value) {
        if (linkText.value) {
            // Se há texto, substitui a seleção
            editor.value.chain().focus()
                .insertContent(`<a href="${linkUrl.value}">${linkText.value}</a>`)
                .run();
        } else {
            // Se não há texto, define link na seleção atual
            editor.value.chain().focus()
                .setLink({ href: linkUrl.value })
                .run();
        }
        showLinkModal.value = false;
    }
};

const removeLink = () => {
    editor.value.chain().focus().unsetLink().run();
};

// Funções para fonte
const toggleFontDropdown = () => {
    showFontDropdown.value = !showFontDropdown.value;
};

const setFontFamily = (fontValue) => {
    editor.value.chain().focus().setFontFamily(fontValue).run();
    showFontDropdown.value = false;
};

const getCurrentFont = () => {
    if (!editor.value) return 'Arial';
    const currentFont = editor.value.getAttributes('textStyle').fontFamily;
    if (!currentFont) return 'Arial';
    
    // Encontrar a fonte na lista de fontes disponíveis
    const font = availableFonts.find(f => f.value === currentFont);
    return font ? font.name : currentFont.split(',')[0];
};

// Função para upload de arquivo de imagem
const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageUrl.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};
</script>

<template>
    <div v-bind="attrs" class="border rounded-lg" @click="showFontDropdown = false">
        <section
            v-if="editor"
            class="buttons flex text-azul-oxford items-center flex-wrap gap-2 border-b border-gray-200 p-4 bg-gray-50"
            @click.stop
        >
            <!-- Formatação básica -->
            <div class="flex items-center gap-1 border-r border-gray-300 pr-2">
                <button
                    @click="editor.chain().focus().toggleBold().run()"
                    :disabled="!editor.can().chain().focus().toggleBold().run()"
                    :class="{ 'bg-blue-200': editor.isActive('bold') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Negrito"
                >
                    <BoldIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().toggleItalic().run()"
                    :disabled="!editor.can().chain().focus().toggleItalic().run()"
                    :class="{ 'bg-blue-200': editor.isActive('italic') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Itálico"
                >
                    <ItalicIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().toggleUnderline().run()"
                    :disabled="!editor.can().chain().focus().toggleUnderline().run()"
                    :class="{ 'bg-blue-200': editor.isActive('underline') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Sublinhado"
                >
                    <SublinharIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().toggleStrike().run()"
                    :disabled="!editor.can().chain().focus().toggleStrike().run()"
                    :class="{ 'bg-blue-200': editor.isActive('strike') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Riscado"
                >
                    <StrikeIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().toggleHighlight().run()"
                    :class="{ 'bg-blue-200': editor.isActive('highlight') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Destacar"
                >
                    <HighlightIcon class="w-4 h-4" />
                </button>
            </div>

            <!-- Títulos -->
            <div class="flex items-center gap-1 border-r border-gray-300 pr-2">
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                    :class="{ 'bg-blue-200': editor.isActive('heading', { level: 1 }) }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors text-xs font-bold"
                    title="Título 1"
                >
                    H1
                </button>
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                    :class="{ 'bg-blue-200': editor.isActive('heading', { level: 2 }) }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors text-xs font-bold"
                    title="Título 2"
                >
                    H2
                </button>
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                    :class="{ 'bg-blue-200': editor.isActive('heading', { level: 3 }) }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors text-xs font-bold"
                    title="Título 3"
                >
                    H3
                </button>
            </div>

            <!-- Seletor de Fonte -->
            <div class="relative flex items-center gap-1 border-r border-gray-300 pr-2">
                <button
                    @click="toggleFontDropdown"
                    class="p-2 rounded hover:bg-blue-100 transition-colors text-xs font-medium min-w-[100px] text-left flex items-center justify-between"
                    title="Selecionar fonte"
                >
                    <span class="truncate">{{ getCurrentFont() }}</span>
                    <svg class="w-3 h-3 ml-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <!-- Dropdown de fontes -->
                <div
                    v-if="showFontDropdown"
                    @click.stop
                    class="absolute top-full left-0 mt-1 w-56 bg-white border border-gray-300 rounded-md shadow-xl z-50 max-h-72 overflow-y-auto"
                >
                    <div class="py-1">
                        <button
                            v-for="font in availableFonts"
                            :key="font.value"
                            @click="setFontFamily(font.value)"
                            class="w-full text-left px-4 py-3 text-sm hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-b-0 flex items-center"
                            :style="{ fontFamily: font.value }"
                            :class="{ 'bg-blue-100 text-blue-800': getCurrentFont() === font.name }"
                        >
                            <span class="flex-1">{{ font.name }}</span>
                            <span v-if="getCurrentFont() === font.name" class="text-blue-600 ml-2">✓</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Alinhamento -->
            <div class="flex items-center gap-1 border-r border-gray-300 pr-2">
                <button
                    @click="editor.chain().focus().setTextAlign('left').run()"
                    :class="{ 'bg-blue-200': editor.isActive({ textAlign: 'left' }) }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Alinhar à esquerda"
                >
                    <AlignLeftIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().setTextAlign('center').run()"
                    :class="{ 'bg-blue-200': editor.isActive({ textAlign: 'center' }) }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Centralizar"
                >
                    <AlignCenterIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().setTextAlign('right').run()"
                    :class="{ 'bg-blue-200': editor.isActive({ textAlign: 'right' }) }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Alinhar à direita"
                >
                    <AlignRightIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().setTextAlign('justify').run()"
                    :class="{ 'bg-blue-200': editor.isActive({ textAlign: 'justify' }) }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Justificar"
                >
                    <AlignJustifyIcon class="w-4 h-4" />
                </button>
            </div>

            <!-- Listas -->
            <div class="flex items-center gap-1 border-r border-gray-300 pr-2">
                <button
                    @click="editor.chain().focus().toggleBulletList().run()"
                    :class="{ 'bg-blue-200': editor.isActive('bulletList') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Lista com marcadores"
                >
                    <ListaIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().toggleOrderedList().run()"
                    :class="{ 'bg-blue-200': editor.isActive('orderedList') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Lista numerada"
                >
                    <ListaOrdenadaIcon class="w-4 h-4" />
                </button>
            </div>

            <!-- Mídia e Links -->
            <div class="flex items-center gap-1 border-r border-gray-300 pr-2">
                <button
                    @click="openImageModal"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Inserir imagem"
                >
                    <ImageIcon class="w-4 h-4" />
                </button>
                <button
                    @click="openLinkModal"
                    :class="{ 'bg-blue-200': editor.isActive('link') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Inserir link"
                >
                    <LinkIcon class="w-4 h-4" />
                </button>
                <button
                    v-if="editor.isActive('link')"
                    @click="removeLink"
                    class="p-2 rounded hover:bg-red-100 text-red-600 transition-colors"
                    title="Remover link"
                >
                    ✕
                </button>
            </div>

            <!-- Outros elementos -->
            <div class="flex items-center gap-1 border-r border-gray-300 pr-2">
                <button
                    @click="editor.chain().focus().toggleBlockquote().run()"
                    :class="{ 'bg-blue-200': editor.isActive('blockquote') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Citação"
                >
                    <ComentarioIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().toggleCodeBlock().run()"
                    :class="{ 'bg-blue-200': editor.isActive('codeBlock') }"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Bloco de código"
                >
                    <CodigoIcon class="w-4 h-4" />
                </button>
                <button 
                    @click="editor.chain().focus().setHorizontalRule().run()"
                    class="p-2 rounded hover:bg-blue-100 transition-colors"
                    title="Linha horizontal"
                >
                    <LinhaIcon class="w-4 h-4" />
                </button>
            </div>

            <!-- Undo/Redo -->
            <div class="flex items-center gap-1">
                <button
                    @click="editor.chain().focus().undo().run()"
                    :disabled="!editor.can().chain().focus().undo().run()"
                    class="p-2 disabled:opacity-30 rounded hover:bg-blue-100 transition-colors"
                    title="Desfazer"
                >
                    <UndoIcon class="w-4 h-4" />
                </button>
                <button
                    @click="editor.chain().focus().redo().run()"
                    :disabled="!editor.can().chain().focus().redo().run()"
                    class="p-2 disabled:opacity-30 rounded hover:bg-blue-100 transition-colors"
                    title="Refazer"
                >
                    <RedoIcon class="w-4 h-4" />
                </button>
            </div>
        </section>

        <EditorContent
            :editor="editor"
            class="prose prose-sm sm:prose lg:prose-lg mx-auto focus:outline-none p-6 min-h-[400px] max-h-[600px] overflow-y-auto"
        />
        
        <p v-if="error" class="text-sm text-red-600 p-4">{{ error }}</p>
    </div>

    <!-- Modal para inserir imagem -->
    <div v-if="showImageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Inserir Imagem</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    URL da Imagem
                </label>
                <input
                    v-model="imageUrl"
                    type="url"
                    placeholder="https://exemplo.com/imagem.jpg"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ou fazer upload
                </label>
                <input
                    type="file"
                    accept="image/*"
                    @change="handleImageUpload"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Texto alternativo (opcional)
                </label>
                <input
                    v-model="imageAlt"
                    type="text"
                    placeholder="Descrição da imagem"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <div class="flex justify-end space-x-3">
                <button
                    @click="showImageModal = false"
                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50"
                >
                    Cancelar
                </button>
                <button
                    @click="insertImage"
                    :disabled="!imageUrl"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                    Inserir
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para inserir link -->
    <div v-if="showLinkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Inserir Link</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    URL do Link
                </label>
                <input
                    v-model="linkUrl"
                    type="url"
                    placeholder="https://exemplo.com"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Texto do Link
                </label>
                <input
                    v-model="linkText"
                    type="text"
                    placeholder="Texto que aparecerá como link"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <div class="flex justify-end space-x-3">
                <button
                    @click="showLinkModal = false"
                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50"
                >
                    Cancelar
                </button>
                <button
                    @click="insertLink"
                    :disabled="!linkUrl"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                    Inserir
                </button>
            </div>
        </div>
    </div>
</template>

<style>
.ProseMirror {
    > * + * {
        margin-top: 0.75em;
    }

    p {
        margin: 0.5em 0;
        line-height: 1.6;
    }

    h1, h2, h3, h4, h5, h6 {
        margin: 1em 0 0.5em 0;
        line-height: 1.2;
        font-weight: bold;
    }

    h1 { font-size: 2em; }
    h2 { font-size: 1.5em; }
    h3 { font-size: 1.25em; }

    ul, ol {
        margin: 0.5em 0;
        padding: 0 1.5rem;
    }

    li > p {
        margin: 0.25em 0;
    }

    blockquote {
        margin: 1em 0;
        padding-left: 1rem;
        border-left: 4px solid #e5e7eb;
        font-style: italic;
        color: #6b7280;
    }

    code {
        background-color: #f3f4f6;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-size: 0.875em;
    }

    pre {
        background-color: #1f2937;
        color: #f9fafb;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1em 0;
    }

    pre code {
        background: none;
        padding: 0;
        color: inherit;
    }

    img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1rem 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    a {
        color: #2563eb;
        text-decoration: underline;
        transition: color 0.2s;
    }

    a:hover {
        color: #1d4ed8;
    }

    hr {
        margin: 2rem 0;
        border: none;
        border-top: 2px solid #e5e7eb;
    }

    /* Placeholder styling */
    p.is-editor-empty:first-child::before {
        content: attr(data-placeholder);
        float: left;
        color: #9ca3af;
        pointer-events: none;
        height: 0;
    }

    /* Text alignment */
    .text-left { text-align: left; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-justify { text-align: justify; }

    /* Remove default outline */
    outline: none !important;

    &:focus {
        outline: none !important;
        box-shadow: none !important;
    }
}

/* Ajusta as classes do prose do Tailwind */
.prose {
    max-width: none !important;
    line-height: 1.6 !important;
}

.prose p {
    margin: 0.5em 0 !important;
}

.prose img {
    margin: 1rem auto !important;
    display: block;
}

.prose blockquote {
    border-left-color: #3b82f6 !important;
}

/* Estilos para os botões da toolbar */
.buttons button:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.buttons button:active {
    transform: translateY(0);
}

/* Melhor aparência para links ativos */
.prose a.ProseMirror-selectednode {
    background-color: #dbeafe;
    border-radius: 0.25rem;
    padding: 0.125rem 0.25rem;
}
</style>
