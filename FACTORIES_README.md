# Factories e Seeders - Portal Bikanca

Este documento explica como usar as factories e seeders para gerar dados de teste realistas para o Portal Bikanca.

## 📊 Dados Gerados

### Quantidades Padrão
- **1 usuário** admin para login
- **8 categorias** predefinidas (Política, Economia, Esportes, etc.)
- **43 tags** diversas e realistas
- **10 autores** (5 predefinidos + 5 gerados)
- **50 notícias** com conteúdo realista
- **50 imagens** associadas às notícias

### Distribuição de Status das Notícias
- **70%** publicadas (com data de publicação e visualizações)
- **20%** rascunho
- **10%** arquivadas

## 🎯 Factories Disponíveis

### CategoriaFactory
Gera categorias realistas de um portal de notícias:
```php
Categoria::factory()->create();
Categoria::factory(5)->create(); // Cria 5 categorias
```

### TagFactory
Cria tags relacionadas a temas jornalísticos:
```php
Tag::factory()->create();
Tag::factory(10)->create(); // Cria 10 tags
```

### AutorFactory
Gera jornalistas com biografias realistas:
```php
Autor::factory()->create();
Autor::factory()->create([
    'nome' => 'Nome Específico',
    'email' => 'email@exemplo.com'
]);
```

### NoticiaFactory
Cria notícias com títulos, conteúdo e estrutura jornalística:
```php
Noticia::factory()->create([
    'autor_id' => 1,
    'categoria_id' => 2,
]);

// Para notícia específica
Noticia::factory()->publicada()->create(); // Status: publicada
Noticia::factory()->rascunho()->create();  // Status: rascunho
```

### MidiaFactory
Gera imagens de capa com URLs do Unsplash:
```php
Midia::factory()->create();
```

## 🚀 Como Usar

### Recrear Dados Completos
```bash
php artisan migrate:fresh --seed
```

### Adicionar Apenas Notícias
```bash
php artisan tinker
```
```php
// No tinker
$autores = App\Models\Autor::all();
$categorias = App\Models\Categoria::all();
$tags = App\Models\Tag::all();

for ($i = 0; $i < 20; $i++) {
    $noticia = App\Models\Noticia::factory()->create([
        'autor_id' => $autores->random()->id,
        'categoria_id' => $categorias->random()->id,
    ]);
    
    $noticia->tags()->attach($tags->random(rand(1, 4))->pluck('id'));
}
```

### Criar Dados Específicos
```php
// Criar categoria específica
Categoria::create([
    'nome' => 'Tecnologia',
    'slug' => 'tecnologia',
    'descricao' => 'Notícias sobre tecnologia e inovação'
]);

// Criar autor específico
$autor = Autor::factory()->create([
    'nome' => 'João Silva',
    'email' => 'joao@portal.com'
]);

// Criar notícia específica
$noticia = Noticia::factory()->create([
    'titulo' => 'Título específico da notícia',
    'autor_id' => $autor->id,
    'categoria_id' => 1,
    'status' => 'publicada'
]);
```

## 📝 Estrutura dos Dados

### Categorias Padrão
1. Política
2. Economia  
3. Esportes
4. Tecnologia
5. Saúde
6. Educação
7. Cultura
8. Internacional

### Tags Disponíveis
Eleições, Congresso, Presidente, COVID-19, Olimpíadas, Copa do Mundo, Futebol, Inteligência Artificial, Smartphone, Universidade, ENEM, Cinema, Música, Pesquisa, NASA, Sustentabilidade, Mercado, Bolsa, Segurança Pública, Viagem, Turismo, entre outras.

### Autores Predefinidos
- **Maria Silva Santos** - Política (15 anos)
- **João Pedro Oliveira** - Esportes (8 anos)
- **Ana Carolina Lima** - Economia (MBA)
- **Carlos Eduardo Costa** - Internacional
- **Fernanda Rocha** - Cultura

## 🎨 Características das Notícias Geradas

### Títulos Realistas
- "Governo anuncia nova medida para educação pública"
- "Estudo revela que tecnologia 5G revoluciona comunicações"
- "Empresa brasileira desenvolve solução sustentável"

### Conteúdo Estruturado
- Parágrafo de abertura
- 3-5 parágrafos de desenvolvimento
- Citações de especialistas
- 2-3 parágrafos de conclusão

### Imagens
- URLs do Unsplash categorizadas por tema
- Legendas e créditos realistas
- Diferentes formatos (capa, destaque, etc.)

## 🔧 Personalização

Para personalizar os dados gerados, edite os arquivos:
- `database/factories/NoticiaFactory.php` - Títulos e conteúdo
- `database/factories/AutorFactory.php` - Perfis de jornalistas  
- `database/seeders/DatabaseSeeder.php` - Quantidades e dados específicos

## 📈 Performance

O seeder atual cria dados de forma otimizada:
- Reutiliza autores e categorias existentes
- Evita dependências circulares
- Gera conteúdo variado mas consistente
- Tempo médio: ~30 segundos para 50 notícias
