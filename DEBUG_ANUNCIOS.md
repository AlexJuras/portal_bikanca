# Debug - Adicionar Anúncios à Página

## ✅ Status Atual

A funcionalidade está **tecnicamente funcionando**:
- ✅ Banco de dados aceita inserções
- ✅ Validações estão corretas
- ✅ Rotas configuradas corretamente

## 🔍 Como Verificar Logs

### 1. Logs do Laravel (Backend)

```bash
tail -f storage/logs/laravel.log
```

Você verá:
```
[data] local.INFO: AnuncioPagina store - Dados recebidos: {"anuncio_id":"X","pagina":"noticias","ordem":"2"}
[data] local.INFO: AnuncioPagina store - Dados validados: {...}
[data] local.INFO: AnuncioPagina store - Posição ocupada?: {"existe":false}
[data] local.INFO: AnuncioPagina store - Anúncio já atribuído?: {"existe":false}
[data] local.INFO: AnuncioPagina store - Criado com sucesso: {"id":15}
```

### 2. Console do Navegador (Frontend)

Abra o DevTools (F12) → aba **Console**

Você verá:
```javascript
Enviando dados: {anuncio_id: 59, pagina: "noticias", ordem: 2}
Sucesso: {...}
Requisição finalizada
```

Se houver erro de validação, aparecerá um **alert** com a mensagem.

## 🐛 Possíveis Problemas

### Problema 1: Posição já ocupada
**Sintoma:** Nada acontece ao clicar em "Adicionar Anúncio"
**Causa:** A posição escolhida já está ocupada por outro anúncio
**Solução:** Escolha uma posição diferente (1, 2 ou 3)

**Verificar posições ocupadas:**
```bash
php artisan tinker --execute="App\Models\AnuncioPagina::where('pagina', 'noticias')->get(['ordem', 'anuncio_id'])"
```

### Problema 2: Anúncio já atribuído
**Sintoma:** Erro "Este anúncio já está atribuído a esta página"
**Causa:** Você está tentando adicionar o mesmo anúncio duas vezes na mesma página
**Solução:** Escolha um anúncio diferente

**Verificar anúncios atribuídos:**
```bash
php artisan tinker --execute="App\Models\AnuncioPagina::where('pagina', 'noticias')->with('anuncio')->get()"
```

### Problema 3: Sessão/CSRF expirado
**Sintoma:** Erro 419 ou "CSRF token mismatch"
**Causa:** Página ficou aberta por muito tempo
**Solução:** Recarregue a página (F5) e tente novamente

### Problema 4: Cache do navegador
**Sintoma:** Comportamento estranho, código antigo sendo executado
**Causa:** Navegador está usando versão antiga do JavaScript
**Solução:** 
1. Limpe o cache (Ctrl+Shift+Del)
2. Ou force reload (Ctrl+F5)
3. Ou abra em aba anônima

## 🧪 Teste Manual via Terminal

Para testar se o backend está funcionando:

```bash
cd /mnt/HD/Projetos/portal_bikanca

# Ver anúncios disponíveis
php artisan tinker --execute="App\Models\Anuncio::where('ativo_global', true)->get(['id', 'nome'])"

# Ver posições ocupadas na página noticias
php artisan tinker --execute="App\Models\AnuncioPagina::where('pagina', 'noticias')->get(['id', 'anuncio_id', 'ordem'])"

# Adicionar manualmente (teste)
php artisan tinker --execute="App\Models\AnuncioPagina::create(['anuncio_id' => 10, 'pagina' => 'noticias', 'ordem' => 2])"

# Remover teste
php artisan tinker --execute="App\Models\AnuncioPagina::where('pagina', 'noticias')->where('ordem', 2)->delete()"
```

## 📝 Estrutura Atual

**Página:** noticias
**Anúncios atribuídos:** 1
- Posição 1: Anúncio ID 59 (gif) - Ativo

**Posições disponíveis:** 2 e 3

## ✅ Para Adicionar Novo Anúncio

1. Selecione um anúncio da lista (exemplo: "Banner Topo - Listagem de Notícias")
2. Selecione posição **2** ou **3** (posição 1 já está ocupada)
3. Clique em "Adicionar Anúncio"
4. Verifique console do navegador (F12) e logs do Laravel

## 🔄 Se Nada Funcionar

1. **Limpe o cache de configuração:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

2. **Recompile os assets:**
```bash
npm run build
```

3. **Reinicie o servidor:**
```bash
php artisan serve
```

4. **Verifique permissões:**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```
