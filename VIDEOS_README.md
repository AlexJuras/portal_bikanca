# Sistema de Vídeos - Portal Bikanca

## 📹 Visão Geral

O sistema de vídeos permite gerenciar e exibir vídeos do YouTube de forma organizada no portal. Os vídeos são categorizados e podem ter diferentes status de publicação.

## 🎯 Funcionalidades

### 🔧 Área Administrativa (`/admin/videos`)

- **Listagem de Vídeos**: Visualização de todos os vídeos com paginação e busca
- **Criação**: Formulário para adicionar novos vídeos
- **Edição**: Atualização de vídeos existentes
- **Exclusão**: Remoção de vídeos com confirmação
- **Estatísticas**: Dashboard com métricas de vídeos
- **Filtros**: Busca por título com debounce
- **Preview**: Visualização prévia de vídeos do YouTube

### 🌐 Área Pública (`/videos`)

- **Listagem Pública**: Grid responsivo de vídeos publicados
- **Visualização Individual**: Página dedicada para cada vídeo
- **Categorização**: Filtros por categoria
- **Sidebar**: Vídeos mais assistidos
- **Player Embed**: Reprodução direta do YouTube
- **Contadores**: Visualizações e estatísticas

## 📊 Estrutura de Dados

### Tabela: `midias` (para vídeos)

```sql
- id (bigint, primary key)
- tipo (string) = 'video'
- titulo (string, required)
- descricao (text, required)
- url_externa (string, required) - URL do YouTube
- thumbnail (string, nullable) - URL personalizada da thumbnail
- duracao (string, nullable) - Ex: "15:30"
- categoria_id (foreign key)
- autor_id (foreign key)
- visualizacoes (integer, default: 0)
- status (enum: rascunho|publicada)
- publicada_em (datetime, nullable)
- created_at/updated_at (timestamps)
```

## 🔗 Rotas

### Área Administrativa
```php
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::prefix('videos')->name('admin.videos.')->group(function () {
        Route::get('/', [VideoController::class, 'admin'])->name('index');
        Route::get('/create', [VideoController::class, 'create'])->name('create');
        Route::post('/', [VideoController::class, 'store'])->name('store');
        Route::get('/{midia}/edit', [VideoController::class, 'edit'])->name('edit');
        Route::put('/{midia}', [VideoController::class, 'update'])->name('update');
        Route::delete('/{midia}', [VideoController::class, 'destroy'])->name('destroy');
    });
});
```

### Área Pública
```php
Route::prefix('videos')->name('videos.')->group(function () {
    Route::get('/', [VideoController::class, 'index'])->name('index');
    Route::get('/categoria/{categoria}', [VideoController::class, 'categoria'])->name('categoria');
    Route::get('/{midia}', [VideoController::class, 'show'])->name('show');
});
```

## 🎨 Páginas Vue.js

### Área Administrativa
- `Admin/Videos/Index.vue` - Listagem com busca e estatísticas
- `Admin/Videos/Create.vue` - Formulário de criação com preview
- `Admin/Videos/Edit.vue` - Formulário de edição

### Área Pública
- `Videos/Index.vue` - Grid de vídeos publicados
- `Videos/Show.vue` - Página individual do vídeo

## 🔧 Funcionalidades Técnicas

### Extração Automática de Dados do YouTube
- **ID do YouTube**: Extraído automaticamente da URL
- **Thumbnail**: Gerada automaticamente se não fornecida
- **URL de Embed**: Criada automaticamente para o player

### Validações
- URL deve ser válida do YouTube
- Título e descrição obrigatórios
- Categoria e autor devem existir
- Thumbnail deve ser URL válida (se fornecida)

### Recursos Especiais
- **Preview de Vídeo**: Visualização prévia na criação
- **Contador de Visualizações**: Incrementado automaticamente
- **Responsive Design**: Interface adaptada para todos os dispositivos
- **SEO Friendly**: URLs amigáveis e meta dados

## 📱 Interface do Usuário

### Design System
- **Grid Responsivo**: 1 coluna (mobile) → 2 (tablet) → 3 (desktop)
- **Cards de Vídeo**: Thumbnail, título, descrição e meta dados
- **Player Embed**: YouTube incorporado com configurações otimizadas
- **Sidebar**: Vídeos relacionados e mais assistidos

### Elementos Visuais
- **Thumbnails**: Aspect ratio 16:9 com overlay de play
- **Badges**: Status, categoria e duração
- **Hover Effects**: Transformações suaves nos cards
- **Loading States**: Lazy loading para imagens

## 🔍 Busca e Filtros

### Funcionalidades
- **Busca por Título**: Com debounce para performance
- **Filtro por Categoria**: Listagem específica por categoria
- **Filtro por Status**: Publicados vs rascunhos (admin)
- **Ordenação**: Por data de publicação (mais recentes primeiro)

## 📈 Estatísticas e Analytics

### Métricas Disponíveis
- Total de vídeos
- Vídeos publicados vs rascunhos
- Contadores de visualização
- Vídeos mais assistidos

## 🛠️ Uso do Sistema

### Para Administradores

1. **Adicionar Novo Vídeo**:
   - Acesse `/admin/videos/create`
   - Cole a URL do YouTube
   - Preencha título e descrição
   - Selecione categoria e autor
   - Configure status (rascunho/publicada)
   - Salve

2. **Gerenciar Vídeos**:
   - Liste todos em `/admin/videos`
   - Use a busca para encontrar específicos
   - Edite clicando no botão de edição
   - Exclua com confirmação

### Para Usuários Públicos

1. **Navegar Vídeos**:
   - Acesse `/videos` para ver todos
   - Clique em categorias para filtrar
   - Use a sidebar para vídeos populares

2. **Assistir Vídeos**:
   - Clique no card do vídeo
   - Player será carregado automaticamente
   - Visualização será contabilizada

## 🔧 Configurações Técnicas

### Dependências
- Laravel 11
- Inertia.js
- Vue.js 3
- Tailwind CSS

### Otimizações
- Lazy loading de imagens
- Paginação eficiente
- Cache de thumbnails do YouTube
- Debounce na busca

## 📋 Próximas Funcionalidades

- [ ] Sistema de comentários
- [ ] Avaliações (likes/dislikes)
- [ ] Playlists organizadas
- [ ] Upload de legendas
- [ ] Integração com analytics
- [ ] Notificações de novos vídeos
- [ ] Sistema de favoritos
- [ ] Compartilhamento social

## 🚀 Como Testar

1. **Área Admin**: Faça login e acesse `/admin/videos`
2. **Criar Vídeo**: Use qualquer URL do YouTube
3. **Área Pública**: Visite `/videos` para ver o resultado
4. **Visualizar**: Clique em um vídeo para ver a página individual

---

Sistema desenvolvido para o Portal Bikanca com foco em usabilidade e performance.
