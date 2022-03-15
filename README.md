# Tres en raya

Este programa es una prueba de PHP para Híberus, está realizado con Symfony 5.4 y bootstrap 5

## Comenzando 🚀

Para usarlo de manera local basta con clonar el repositorio en local, una vez clonado, realizar los siguientes pasos:
* Ejecutar en consola en la raíz del proyecto:
```shell
composer update
```
* Crear la base de datos.
* Ejecutar en consola en la raíz del proyecto:
```shell
php bin/console doctrine:schema:update --force
```
Para crear las tablas según se han definido en las entidades y ya se podría utilizar en local.

Más adelante se explica cómo desplagar mediante docker

Mira **[Despliegue](#despliegue)** para conocer como desplegar el proyecto en docker.


### Pre-requisitos 📋

Para poder desplegar esta aplicación mediante Docker, se necesitan los siguientes requisitos:

* _Composer instalado globalmente._
* _Docker Desktop._
* _La utilidad Symfony CLI._

<a name="despliegue"></a>
## Despliegue 📦

En este caso ya están los archivos creados, pero en el caso de que no estuvieran, la estructura necesaria es la siguiente:

* ./ <- esto es la raíz del proyecto.
* ./docker-compose.yml
* ./php/Dockerfile
* ./nginx/default.conf

Ejecutar el siguiente comando en la raíz del proyecto para construir los contenedores:

````shell
docker-compose up -d --build
````
Y este otro para acceder al que contendrá la aplicación:

````shell
docker-compose exec php /bin/bash
````

Con este último se accede al directorio raíz del proyecto dentro del contenedor, es decir, donde se deben colocar todos los archivos correspondientes al proyecto.
Esto se puede hacer, por ejemplo, mediante gestión de versiones, en este caso Git.

Si aún no se ha creado el repositorio para el proyecto, se crea y se sincronizan los archivos de local con los del repositorio.

Una vez hecho esto, desde el contenedor, se obtienen los archivos del repositorio y se ejecutan los siguientes comandos:

Actualizar con composer todas las dependencias del proyecto:

````shell
composer update
````

Crear las tablas y restricciones de la base de datos:

````shell
php bin/console doctrine:schema:update --force
````

Con esto ya se puede ejecutar la aplicación en http://localhost:8080/

## Construido con 🛠️

Para la ejecución de este proyecto se han utilizado las siguientes herramientas:

* [Symfony](https://symfony.com/) - El framework web usado.
* [Composer](https://maven.apache.org/) - Manejador de dependencias.
* [GitHub](https://github.com/rubenbitrian/tresenraya) - Usado para la gestión de versiones.

## Licencia 📄

Este proyecto está bajo la Licencia GPU-GPL v.3.0 - mira el archivo [LICENSE.md](LICENSE.md) para más detalles.

