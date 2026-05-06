# 📌 ABase — Documentação Técnica (Versão Beta)

## 🧠 Visão Geral

O **ABase** é uma aplicação web para descoberta e gerenciamento de eventos com base em geolocalização.

O sistema permite:

* Buscar eventos próximos via localização
* Visualizar eventos em lista e mapa
* Salvar eventos no perfil
* Realizar check-in
* Avaliar eventos
* Criar e gerenciar eventos (produtores)

⚠️ Esta é uma versão **beta**, focada em demonstrar conhecimentos técnicos (PHP, CSS, JS, integração com API).

---

## 🏗️ Arquitetura do Sistema

O projeto segue uma estrutura inspirada em **MVC (Model-View-Controller)**:

### 📁 Estrutura de Pastas

```
/config
/controllers
/views
/assets/css
/assets/img
```

### 🔹 Responsabilidades

* **config/**

  * Conexão com banco de dados (`config.php`)

* **controllers/**

  * Lógica do sistema (ex: check-in, cadastro, busca de eventos)

* **views/**

  * Interface do usuário (HTML + PHP)

* **assets/css/**

  * Estilização modular

---

## 🎨 Front-End

### HTML

* Estrutura das páginas
* Uso de tags semânticas básicas (`div`, `button`, `input`, etc.)

### CSS (Modularização)

Separação por responsabilidade:

* `global.css`

  * Reset
  * Inputs
  * Botões base

* `components.css`

  * Cards
  * Botões reutilizáveis
  * Modal padrão

* `mapa.css`

  * Layout da tela de mapa
  * Grid de eventos
  * Estilização específica

---

### 🧩 Componentização

Criado padrão de botões reutilizáveis:

```css
.btn
.btn-success
.btn-warning
.btn-primary
```

E evolução para:

```css
.btn-ingresso
.btn-detalhe
.btn-ja-comprei
```

---

### Layout

* **Flexbox** → alinhamento de botões
* **Grid** → listagem de eventos
* **Responsividade básica** com `max-width` e `auto-fill`

---

## ⚙️ Back-End (PHP)

### Funcionalidades principais

* Autenticação com sessão (`$_SESSION`)
* CRUD básico de eventos
* Associação usuário-evento
* Check-in
* Avaliação
* Busca por localização

---

## 🌍 Geolocalização e Busca

### Fluxo:

1. Usuário informa localização ou usa GPS
2. Sistema obtém:

   * Latitude
   * Longitude
3. Envia para backend (`buscar_eventos.php`)
4. SQL calcula distância via **fórmula de Haversine**
5. Retorna eventos dentro do raio

---

### 📐 Fórmula usada (SQL)

```sql
(6371 * ACOS(
    COS(RADIANS($lat_user)) *
    COS(RADIANS(latitude_local)) *
    COS(RADIANS(longitude_local) - RADIANS($long_user)) +
    SIN(RADIANS($lat_user)) *
    SIN(RADIANS(latitude_local))
)) AS distancia
```

---

## 🗺️ Mapa (Leaflet)

Biblioteca utilizada:

* Leaflet.js

### Funcionalidades:

* Renderização do mapa
* Marcadores de eventos
* Localização do usuário
* Toggle de exibição

---

## 🔗 APIs Utilizadas

### OpenStreetMap (Nominatim)

* Busca de localização por texto
* Autocomplete simples

---

## 🔄 Comunicação Front ↔ Back

### Fetch API

* Requisições AJAX para PHP
* Retorno em JSON

Exemplo:

```js
fetch('buscar_eventos.php')
```

---

## 🗃️ Banco de Dados

### Tabelas principais:

#### usuario

* id_usuario
* email
* senha
* tipo_usuario
* status_aprov

#### evento

* id_evento
* nome
* detalhes
* latitude_local
* longitude_local
* data_evento
* hora_inicio
* url_compra
* id_produtor

#### usuario_evento

* id_usuario
* id_evento
* checkin_realizado

---

### Relacionamentos

* 1 usuário → N eventos (como produtor)
* N usuários ↔ N eventos (participação)

---

## 🧪 Funcionalidades Implementadas

### Usuário

* Cadastro e login
* Visualizar perfil
* Salvar evento
* Check-in
* Avaliar evento

### Produtor

* Criar evento
* Editar evento
* Visualizar próprios eventos

### Mapa

* Buscar por localização
* Buscar por raio
* Autocomplete
* Visualização em mapa

---

## 🧱 Decisões Técnicas

### ✔ PHP puro

* Objetivo didático
* Entendimento de base

### ✔ Sem framework

* Controle total da lógica
* Aprendizado estrutural

### ✔ CSS modular

* Melhor manutenção
* Evita duplicação

---

## ⚠️ Limitações atuais (Beta)

* Não utiliza Prepared Statements (risco de SQL Injection)
* Sem validação robusta de inputs
* Sem paginação de resultados
* Sem upload de imagens
* UI ainda em evolução

---

## 🔐 Melhorias Necessárias

### Segurança

* Prepared Statements
* Sanitização de dados
* Validação de inputs

### Performance

* Indexação no banco
* Limite de consultas (raio máximo)
* Paginação

### Arquitetura

* Melhor separação de camadas
* Possível adoção de framework (Laravel)

---

## 🚀 Próximos Passos

* Padronização completa de UI
* Melhorias de responsividade
* Sistema de imagens para eventos
* Refatoração de CSS
* Evolução para API REST
* Separação frontend/backend

---

## 🤖 Uso de IA

A IA foi utilizada como:

* Apoio na resolução de problemas
* Otimização de código
* Sugestões de estrutura

⚠️ Sempre validada manualmente

---

## 🎯 Objetivo do Projeto

Mais do que um sistema funcional, o projeto tem como foco:

* Demonstrar domínio de base web (HTML, CSS, JS, PHP)
* Integração com APIs
* Organização de código
* Evolução progressiva de arquitetura

---

## 📌 Status

✔ Funcional (versão beta)
⚠ Em evolução contínua
🚀 Base sólida para expansão
