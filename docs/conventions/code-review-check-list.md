Code Review Check List
======================

General
=======

- Использование сторонних ресурсов (иконки, фото и.т.д..) без указания того, откуда это было взято.
- CASS\Application\Bundles\Frontline: добавленные во CASS\Application\Bundles\Frontline данные описаны в swagger-документации

Instant reject:
---------------
- Наличие констант-массивов (`const MY_CONST = ['some' => 'foo', 'any' => 'bar']`)

- Наличие `isset($arr['foo']) ? $arr['foo'] : null` вместо Null Coalesce Operator

- Наличие `try { ... }catch(\Exception $e){ ... }`

- Doctrine2-код вне репозиториев и сущностей.

- `print_r`, `var_dump`, `echo`, `die`, `exit` кроме случаев, когда они применяются не для дебага.

- Закомментированный код

- `if(getSomething() === null) { ... }`

98% reject:
-----------

- Наличие оператора `switch` вне фабрик

- `declare(strict_types=1);`

Clean-up reject
---------------

- Отсутствие `Return type declaration`, `Scalar type declarations`

Front-end
---------

- Check tab-index
- ParseInt без основания

Swagger
-------

- Прописан ключ security
- Прописаны ответы: 200, 400, 403, 404