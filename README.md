# 🧾 Mini ERP em Laravel

Mini ERP desenvolvido com Laravel, que permite o controle de **Produtos**, **Pedidos**, **Cupons** e **Estoque**, incluindo variações de produto, carrinho de compras com regras de frete, integração com ViaCEP, envio de e-mail e suporte a Webhooks.

---

## ✅ Funcionalidades

-   Cadastro e atualização de produtos com variações e controle de estoque
-   Regras de frete com base no subtotal do pedido
-   Aplicação de cupons com validade e valor mínimo
-   Consulta de endereço via API [ViaCEP](https://viacep.com.br/)
-   API para consumo externo (JSON)

---

## ⚙️ Requisitos

-   PHP >= 8.1
-   Composer
-   MySQL
-   Node.js e npm (opcional, para assets com Laravel Mix)

---

## 🚀 Como rodar o projeto localmente

1. **Clone o repositório:**

```bash
git clone https://github.com/seu-usuario/mini-erp-laravel.git
cd mini-erp-laravel
```

2. **Instale as dependências PHP:**

```bash
composer install
```

3. **Copie o arquivo `.env` de exemplo e configure:**

```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas credenciais:

```bash
DB_DATABASE=erp
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

4. **Execute as migrations:**

```bash
php artisan migrate
```

5. **(Opcional) Compile os assets:**

```bash
npm install
npm run dev
```

6. **Inicie o servidor local:**

```bash
php artisan serve
```

Acesse: http://localhost:8000

---

## 🔗 Api de Consumo

_Listar Produtos_
**Endpoint:** `GET /api/produtos`
**Exemplo de retorno:**

```json
[
    {
        "id": 6,
        "nome": "oi",
        "preco": "22.00",
        "created_at": "2025-06-11T02:27:39.000000Z",
        "updated_at": "2025-06-11T02:27:39.000000Z",
        "variacoes": [...]
    }
]
```

_Criar Produto_
**Endpoint:** `POST /api/produto/new`
**Exemplo de payload:**

```json
{
    "nome": "Produto Teste",
    "preco": 120.5,
    "variacoes": [
        {
            "nome": "Variação 1",
            "quantidade": 10
        },
        {
            "nome": "Variação 2",
            "quantidade": 5
        }
    ]
}
```

_Editar Produto_
**Endpoint:** `PUT /api/produto/{id}`
**Exemplo de payload:**

```json
{
    "nome": "Produto Editado",
    "preco": 150.0,
    "variacoes": [
        {
            "id": 1,
            "nome": "Variação Atualizada",
            "quantidade": 8
        },
        {
            "nome": "Nova Variação",
            "quantidade": 4
        }
    ]
}
```

_Excluir Produto_
**Endpoint:** `DELETE /api/produto/{id}`
**Exemplo de resposta:**

```json
{
    "message": "Produto deletado com sucesso"
}
```
