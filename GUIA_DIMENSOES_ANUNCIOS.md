# Guia de Dimensões de Anúncios - Portal Bikanca

## 📐 Dimensões Recomendadas por Posição

### 🔝 Banner Topo
**Desktop:**
- **Leaderboard:** 728 x 90 pixels
- **Super Leaderboard:** 970 x 90 pixels
- **Billboard:** 970 x 250 pixels

**Mobile:**
- **Mobile Banner:** 320 x 50 pixels
- **Mobile Banner Large:** 320 x 100 pixels

**Uso:** Aparece no topo da página, logo após o menu. Ideal para anúncios horizontais largos.

---

### 🎯 Banner Meio de Conteúdo
**Desktop:**
- **Leaderboard:** 728 x 90 pixels
- **Banner:** 468 x 60 pixels
- **Medium Rectangle:** 300 x 250 pixels (centralizado)

**Mobile:**
- **Mobile Banner:** 320 x 50 pixels
- **Mobile Banner Large:** 320 x 100 pixels

**Uso:** Aparece entre o conteúdo principal (notícias/vídeos). Pode ser mais alto no desktop.

---

### 📱 Banner Lateral
**Desktop:**
- **Medium Rectangle:** 300 x 250 pixels (mais comum)
- **Large Rectangle:** 336 x 280 pixels
- **Half Page:** 300 x 600 pixels (sidebar fixa)
- **Portrait:** 300 x 1050 pixels (sidebar longa)

**Mobile:**
- Não aparece (conteúdo em coluna única)

**Uso:** Sidebar direita ou esquerda. Ótimo para anúncios que ficam visíveis durante scroll.

---

### 🔲 Banner Quadrado
**Todas as Telas:**
- **Square:** 250 x 250 pixels
- **Small Square:** 200 x 200 pixels
- **Medium Square:** 300 x 300 pixels

**Uso:** Cards de conteúdo, grid de anúncios, espaços menores.

---

## 🎨 Especificações Técnicas

### Formatos Aceitos
- **JPEG/JPG:** Recomendado para fotos e imagens com gradientes
- **PNG:** Recomendado para logos e imagens com transparência
- **GIF:** Animações (tamanho máximo 2MB)
- **WebP:** Formato moderno, menor tamanho (recomendado)

### Tamanho Máximo
- **2 MB por arquivo**

### Resolução
- **72 DPI** (para web)
- **@2x para Retina:** Dobro das dimensões para telas de alta resolução

### Compressão
- Use ferramentas como TinyPNG ou Squoosh para otimizar
- Mantenha qualidade acima de 80%

---

## 🚀 Como o Sistema Funciona

### Responsividade Automática
O sistema automaticamente:
- ✅ **Redimensiona** a imagem para caber no espaço disponível
- ✅ **Mantém a proporção** original da imagem
- ✅ **Centraliza** banners menores que o container
- ✅ **Limita altura** para evitar anúncios muito grandes
- ✅ **Adapta para mobile** mudando as dimensões máximas

### Comportamento por Layout

**Banner Topo:**
```
Desktop: max-height: 128px (32 * 4px)
Mobile:  max-height: 96px (24 * 4px)
```

**Banner Meio:**
```
Desktop: max-height: 192px (48 * 4px)
Mobile:  max-height: 128px (32 * 4px)
```

**Banner Lateral:**
```
Desktop: max-height: 240px (60 * 4px)
Mobile:  Não exibido
```

---

## 💡 Dicas de Design

### Para Melhor Visualização:

1. **Use imagens com margem interna:**
   - Deixe 10-20px de espaço nas bordas
   - Evite texto ou logos muito próximos das bordas

2. **Texto legível:**
   - Fonte mínima: 14px
   - Alto contraste entre texto e fundo
   - Evite textos muito longos

3. **Cores:**
   - Use cores que contrastem com o site (azul e laranja)
   - Evite fundos brancos puros (use #F9FAFB)

4. **Call-to-Action:**
   - Botões ou textos claros
   - Frases curtas e diretas
   - Cores chamativas (laranja, verde, vermelho)

5. **Logos e Marcas:**
   - Centralize logos importantes
   - Use alta resolução
   - Considere fundo transparente (PNG)

---

## 📊 Exemplos de Uso

### Exemplo 1: E-commerce
```
Formato: 970x250 (Billboard)
Uso: Banner topo da homepage
Conteúdo: Produto em destaque + preço + botão "Comprar"
```

### Exemplo 2: Serviço Local
```
Formato: 300x250 (Medium Rectangle)
Uso: Sidebar em páginas de notícias
Conteúdo: Logo + telefone + endereço + botão "Contato"
```

### Exemplo 3: Evento
```
Formato: 728x90 (Leaderboard)
Uso: Banner meio do conteúdo
Conteúdo: Data/horário + local + botão "Saiba Mais"
```

---

## 🔧 Ferramentas Recomendadas

### Design:
- **Canva:** Templates prontos para banners
- **Figma:** Design profissional
- **Photoshop:** Edição avançada

### Otimização:
- **TinyPNG:** Compressão de PNG/JPG
- **Squoosh:** Compressão e conversão
- **ImageOptim:** Otimização em lote (Mac)

### Gerador de Dimensões:
- **Google Web Designer:** Banners HTML5
- **Bannerboo:** Banners animados
- **Crello:** Templates de anúncios

---

## ❓ Perguntas Frequentes

**P: Posso usar imagens maiores que as recomendadas?**
R: Sim, mas elas serão redimensionadas automaticamente, o que pode resultar em perda de qualidade ou carregamento mais lento.

**P: O que acontece se minha imagem for muito pequena?**
R: Ela será exibida em seu tamanho original (não será ampliada), podendo parecer pequena na tela.

**P: Como faço para criar anúncios animados?**
R: Use GIFs animados ou código AdSense com banners HTML5.

**P: Posso usar vídeos nos anúncios?**
R: Não diretamente. Use o código AdSense do Google para anúncios em vídeo.

**P: Como faço para testar diferentes tamanhos?**
R: Use a página de visualização de anúncios no admin para ver como ficará antes de publicar.

---

## 📞 Suporte

Caso tenha dúvidas sobre dimensões ou formatação de anúncios:
- Acesse o menu **Admin → Anúncios**
- As dimensões recomendadas aparecem ao criar/editar anúncios
- As imagens são automaticamente ajustadas para melhor visualização

---

**Última atualização:** Novembro 2025
**Versão:** 1.0
