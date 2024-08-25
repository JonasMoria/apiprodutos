
## Rotas e Endpoints API Produtos V1.0

## Headers:
| Header | Valores |
| --- | --- |
| Accept-Language | pt / en / us (padrão: us)
| Authorization | Bearer token

## Principais Endpoints:

### Cadastro:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/register | - |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| email | String | Sim |
| password | String | Sim |

#### JSON: 
```yaml
{
    "email" : "usuario@email.com",
    "password" : "Senha123"
}
```

### Login:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/login | Token válido por 1 hora |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| email | String | Sim |
| password | String | Sim |

#### JSON: 
```yaml
{
    "email" : "usuario@email.com",
    "password" : "Senha123"
}
```

### Registro da loja:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | api/store/register | Cria o cadastro das informações públicas da loja |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| name | String | Sim |
| email | String | Não |
| cnpj | String | Não |
| lat | Float | Sim |
| lon | Float | Sim |

#### JSON: 
```yaml
{
    "name" : "LojaTeste",
    "email" : "lojateste@email.com",
    "cnpj" : "11.111.111/0001-11",
    "lat" : "-23.565563",
    "lon" : "-46.655360"
}
```

### Cadastro de Produtos:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/store/product/register | - |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| name_pt | String | Sim |
| name_en | String | Sim |
| name_es | String | Sim |
| sku | String | Sim |

#### JSON: 
```yaml
{
    "name_pt" : "Produto teste",
    "name_en" : "Test Product",
    "name_es" : "Producto de Prueba",
    "sku" : "sku123"
}
```

## Endpoints de Gerenciamento:

### Lojas:

#### Atualiza dados da loja:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/store/update | Atualiza informações do registro da loja |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| name | String | Não |
| email | String | Não |
| cnpj | String | Não |
| lat | Float | Não |
| lon | Float | Não |

#### Atualiza logo da loja:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/store/update/logo | Atualiza logo da loja |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| base64Image | String | Sim |
* Em "base64Image" deverá ser enviado uma string contendo a imagem desejada codificada conforme o algoritmo base64.

### Produtos:

#### Atualiza informações do produto:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/store/product/update | Atualiza cadastro do produto |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| name_pt | String | Não |
| name_en | String | Não |
| name_es | String | Não |
| sku | String | Não |

#### Atualiza imagem do produto:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/store/product/update/logo | Atualiza imagem do produto |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| base64Image | String | Sim |
* Em "base64Image" deverá ser enviado uma string contendo a imagem desejada codificada conforme o algoritmo base64.

#### Deleta produto:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /api/store/product/delete/{id} | deleta produto |


## Endpoints de Pesquisa:

#### Busca dados de uma loja pelo seu ID:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| GET | /api/store/view/{id} | Visualiza informações da loja |

#### Busca lojas próximas as coordenadas enviadas na requisição num raio determinado pela requisição:
| Requisição | URL | Detalhes |
| --- | --- | --- |
| POST | /store/find/location | Busca lojas próximas conforme coordenadas do usuário |

| Dado | Tipo | Obrigatório |
| --- | --- | --- |
| lat | Float | Sim |
| lon | Float | Sim |
| radius | Float | Sim |


