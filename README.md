# MGFormValidation
Validação de Formulário em PHP sem redirecionamento para POST e GET.

---

## 📦 Instalação

### Via Composer (recomendado)

```bash
composer require mugomes/mgformvalidation
```

### Manual

Copie o arquivo `mgformvalidation.php` para o seu projeto e faça a inclusão.

---

## Exemplo de Uso

```php
if (getenv('REQUEST_METHOD') == 'POST') {
    $mgformvalidation = new mgformvalidation();

    $mgformvalidation->rules([
        'nome' => 'required|min:3|max:5',
        'email' => 'required|email'
    ])->json();

    $mgformvalidation->success(function () {
        echo json_encode(['success' => 'Formulário com dados enviados!']);
    });
}

echo '<style>
.mgformvalidation-error {
    color: #ef4444;
    font-size: 14px;
    margin-top: 4px;
}

.mgformvalidation-success {
    color: #22c55e;
    margin-top: 15px;
    text-align: center;
}
</style>';

echo mgformvalidation::form('post', '');
echo '<h2>Contato</h2>';

echo '<input type="text" name="nome" placeholder="Nome">';
echo mgformvalidation::dataError('mgformvalidation-error', 'nome');

echo '<input type="email" name="email" placeholder="E-mail">';
echo mgformvalidation::dataError('mgformvalidation-error', 'email');

echo '<button type="submit">Enviar</button>';

echo mgformvalidation::dataSuccess('mgformvalidation-success');
echo mgformvalidation::endForm();

echo mgformvalidation::scripts();
```

---

## 💙 Apoie

- GitHub: https://github.com/sponsors/mugomes
- More: https://mugomes.github.io/apoie.html

## 👤 Autor

**Murilo Gomes Julio**

🔗 [https://www.bluice.com.br](https://www.bluice.com.br)

📺 [https://youtube.com/@mugomesoficial](https://youtube.com/@mugomesoficial)

---

## License

The MGFormValidation is provided under:

[SPDX-License-Identifier: LGPL-2.1-only](https://github.com/mugomes/mgformvalidation/blob/main/LICENSE)

Beign under the terms of the GNU Lesser General Public License version 2.1 only.

All contributions to the MGFormValidation are subject to this license.