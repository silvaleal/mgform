# MGForm
Biblioteca de validação para formulários

---

## Instalação

```bash
composer require silvaleal/mgform
```

## Exemplo de Uso
### PHP
```php
$data = (new Validator())->validate([
    'nome' => 'required|min:3|max:5',
    'email' => 'required|email'
]);

print_r($data); // Pegar os dados que o usuário inseriu
```
### HTML
```php
<?php foreach ($_SESSION['errors'] ?? [] as $error => $value): ?>
<div>
    <span><?= $error ?>: </span>
    <span><?= $value ?></span>
</div>
<?php endforeach ?>

<form method="post" data-mgformvalidation>
    <input type="text" name="nome" placeholder="Nome">
    <input type="text" name="clan" placeholder="Clan">
    <input type="text" name="email" placeholder="email">

    <button type="submit">Enviar</button>
</form>
```


# Créditos
Este projeto é um FORK do [MGFormValidation](https://github.com/mugomes/mgformvalidation), a ideia deste fork é trazer a biblioteca para a arquitetura moderna, onde pode ser usada em projetos POO, MVC e etc...
