<p align="center"><a href="https://www.diagro.be" target="_blank"><img src="https://diagro.be/assets/img/diagro-logo.svg" width="400"></a></p>

<p align="center">
<img src="https://img.shields.io/badge/project-lib_laravel_token-yellowgreen" alt="Diagro token library">
<img src="https://img.shields.io/badge/type-library-informational" alt="Diagro service">
<img src="https://img.shields.io/badge/php-8.1-blueviolet" alt="PHP">
<img src="https://img.shields.io/badge/laravel-9.0-red" alt="Laravel framework">
</p>

## Description

This library contains the token classes (AT, AAT and BAT) that are used in the backend and frontend applications that use the Laravel framework.
The base class is `Token` and you should put your own OpenSSL public/private key in there with a passphrase.

The child token classes only specify their payload.

## Installation

* Composer: `diagro/lib_laravel_token: "^1.6"`
