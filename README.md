# MGForm
Biblioteca de validação para formulários

---

## Instalação

```bash
composer require silvaleal/mgform
```

## Exemplo de Uso

```php
$data = (new Validator())->validate([
    'nome' => 'required|min:3|max:5',
    'email' => 'required|email'
]);

print_r($data); // Pegar os dados que o usuário inseriu
```

# Créditos
Este projeto é um FORK do [MGFormValidation](https://github.com/mugomes/mgformvalidation), a ideia deste fork é trazer a biblioteca para a arquitetura moderna, onde pode ser usada em projetos POO, MVC e etc...