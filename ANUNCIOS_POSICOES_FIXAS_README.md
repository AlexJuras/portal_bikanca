# Sistema de Posições Fixas de Anúncios

## 📝 Visão Geral

O sistema de anúncios foi modificado para trabalhar com **posições fixas** pré-definidas. Agora não é possível criar ou excluir anúncios - apenas **ativar/desativar** e **configurar o conteúdo** das posições existentes.

## 🎯 Funcionalidades

### ✅ O que é possível fazer:
- **Ativar/Desativar** posições de anúncios
- **Configurar conteúdo** (imagem, HTML, script)
- **Editar configurações** (link, dimensões, datas, etc.)
- **Visualizar estatísticas** (impressões, cliques, CTR)

### ❌ O que NÃO é possível fazer:
- Criar novas posições de anúncios
- Excluir posições existentes
- Alterar a posição ou página de um anúncio
- Modificar a ordem das posições

## 🗺️ Posições Disponíveis

### Páginas:
- **home** - Página Inicial
- **noticias** - Listagem de Notícias
- **noticia-individual** - Página Individual de Notícia
- **categoria** - Páginas de Categoria
- **videos** - Página de Vídeos
- **todas** - Todas as Páginas

### Posições:
- **banner-topo** - Banner Topo (970x250)
- **banner-lateral-topo** - Banner Lateral Topo (300x250)
- **banner-lateral-meio** - Banner Lateral Meio (300x250)
- **banner-lateral-rodape** - Banner Lateral Rodapé (300x600)
- **banner-meio-conteudo** - Banner Meio do Conteúdo (728x90)
- **banner-rodape** - Banner Rodapé (970x250)
- **banner-flutuante** - Banner Flutuante
- **sidebar-widget** - Widget Sidebar

## 🏗️ Implementação Técnica

### Modificações no Controller
```php
// AnuncioController.php
- Removido: create(), store(), destroy()
- Modificado: index() - agora cria posições fixas automaticamente
- Modificado: update() - não permite alterar posição/página
- Mantido: edit(), toggleAtivo()
```

### Modificações nas Rotas
```php
// routes/web.php
- Removido: create, store, destroy
- Mantido: index, edit, update, toggle-ativo
```

### Modificações no Frontend
```vue
// Index.vue
- Removido: botão "Novo Anúncio"
- Removido: botões "Excluir"
- Modificado: botão "Editar" → "Configurar"
- Removido: modal de confirmação de exclusão

// Edit.vue
- Modificado: campos posição/página são readonly
- Adicionado: avisos de que campos são fixos
```

### Banco de Dados
- As posições são criadas automaticamente no primeiro acesso
- Total: **48 posições** (6 páginas × 8 posições)
- Todas começam **desativadas** por padrão

## 🚀 Como Usar

1. **Acessar Admin → Anúncios**
2. **Escolher uma posição** para configurar
3. **Clicar em "Configurar"**
4. **Preencher o conteúdo:**
   - Nome descritivo
   - Tipo (imagem, HTML ou script)
   - Conteúdo correspondente
   - Link (se aplicável)
   - Configurações de exibição
5. **Ativar o anúncio** (toggle)
6. **Salvar**

## 📊 Tipos de Anúncio

### 1. Imagem com Link
- Upload de imagem
- URL de destino
- Opção nova aba
- Dimensões automáticas

### 2. Código HTML
- HTML personalizado
- CSS inline permitido
- Responsivo

### 3. Script de Terceiros
- Google Ads
- Scripts externos
- Código JavaScript

## 🎨 Configurações Avançadas

- **Dimensões:** Largura e altura específicas
- **Período:** Data início e fim
- **Observações:** Notas internas
- **Nova Aba:** Link abre em nova aba
- **Ordem:** Controlada automaticamente

## 📈 Analytics

Cada anúncio possui:
- **Impressões:** Quantas vezes foi exibido
- **Cliques:** Quantas vezes foi clicado
- **CTR:** Taxa de clique (%)
- **Período:** Controle de datas

## 🔧 Manutenção

### Adicionar Nova Posição
1. Editar `Anuncio::getPosicoes()` no model
2. Será criada automaticamente no próximo acesso

### Adicionar Nova Página
1. Editar `Anuncio::getPaginas()` no model
2. Será criada automaticamente no próximo acesso

### Reset Completo
```bash
php artisan migrate:fresh --seed
```

## 🛡️ Segurança

- Posições não podem ser deletadas acidentalmente
- Campos críticos são readonly
- Validação de tipos de arquivo
- Sanitização de HTML/JavaScript

## 📱 Responsividade

- Interface totalmente responsiva
- Suporte a mobile/tablet
- Prévia de dimensões
- Layout adaptativo

---

**✨ Conclusão:** O sistema agora oferece controle total sobre as posições de anúncios sem o risco de configurações incorretas ou perda de dados.
