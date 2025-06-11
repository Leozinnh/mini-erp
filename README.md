# 🧾 Mini ERP em Laravel

Mini ERP desenvolvido com Laravel, que permite o controle de **Produtos**, **Pedidos**, **Cupons** e **Estoque**, incluindo variações de produto, carrinho de compras com regras de frete, integração com ViaCEP, envio de e-mail e suporte a Webhooks.

---

## ✅ Funcionalidades

-   Cadastro e atualização de produtos com variações e controle de estoque
-   Carrinho de compras com controle em sessão
-   Regras de frete com base no subtotal do pedido
-   Aplicação de cupons com validade e valor mínimo
-   Consulta de endereço via API [ViaCEP](https://viacep.com.br/)
-   Envio de e-mail ao finalizar o pedido
-   Webhook para atualização/cancelamento de pedidos

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

## 🌐 Colocando online (produção)

1. **Suba os arquivos via FTP ou Git no servidor.**

2. **No servidor, execute:**

```bash
cd /var/www/html/mini-erp
composer install --no-dev --optimize-autoloader
```

3. **Configure permissões:**

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .
```

4. **Copie e edite o `.env`:**

```bash
cp .env.example .env
nano .env
```

5. **Rode as migrations:**

```bash
php artisan migrate
```

7. **Configure seu Apache/Nginx para apontar para a pasta `/public`.**

---

## 🔗 Api de Consumo

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
