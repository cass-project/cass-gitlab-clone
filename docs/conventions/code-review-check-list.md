Code Review Check List
======================

PHP
===

Instant reject:
---------------
- Наличие `isset($arr['foo']) ? $arr['foo'] : null` вместо Null Coalesce Operator

- Наличие `try { ... }catch(\Exception $e){ ... }`

- Doctrine2-код вне репозиториев и сущностей.

- `print_r`, `var_dump`, `echo`, `die`, `exit` кроме случаев, когда они применяются не для дебага.

- Закомментированный код

98% reject:
-----------

- Наличие оператора `switch` вне фабрик

- `declare(strict_types=1);`

Clean-up reject
---------------

- Отсутствие `Return type declaration`, `Scalar type declarations`