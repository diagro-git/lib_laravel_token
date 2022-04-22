<p align="center"><a href="https://www.diagro.be" target="_blank"><img src="https://diagro.be/assets/img/diagro-logo.svg" width="400"></a></p>

<p align="center">
<img src="https://img.shields.io/badge/project-lib_laravel_token-yellowgreen" alt="Diagro token library">
<img src="https://img.shields.io/badge/type-library-informational" alt="Diagro service">
<img src="https://img.shields.io/badge/php-8.1-blueviolet" alt="PHP">
<img src="https://img.shields.io/badge/laravel-9.0-red" alt="Laravel framework">
</p>

## Beschrijving

Deze bibliotheek is nodig om de AT en AAT tokens te maken en te decoderen voor alle backend en frontend applicaties gemaakt
in Laravel framework.

## Development

* Composer: `diagro/lib_laravel_token: "^1.4"`

## Production

* Composer: `diagro/lib_laravel_token: "^1.4"`

## Changelog

### V1.5

* **Feature**: Token for backend applications.

### V1.4

* **Bugfix**: cache token wanneer createfromtoken is called.

### V1.3

* **Bugfix**: iat en nbf bugfix

### V1.2

* **Feature**: upgrade naar PHP8.1 en Laravel 9.0

### V1.1

* **Bugfix**: HasDiagroToken trait had foute logica in de **can** methode.
* **Feature**: Unit test added

### V1.0

* **Feature**: decode en encode access tokens
* **Feature**: decode en encode application access tokens
* **Feature**: decoden geeft een user object terug met company, applications en access rights
