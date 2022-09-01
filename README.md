<p align="center">
  <a href="" rel="noopener">
 <img width=200px height=200px src="https://i.imgur.com/6wj0hh6.jpg" alt="Project logo"></a>
</p>

<h3 align="center">Wireless Test</h3>

<div align="center">

[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/kylelobo/The-Documentation-Compendium.svg)](https://github.com/kylelobo/The-Documentation-Compendium/pulls)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](/LICENSE)

</div>

---

<p align="center"> CLI Scraper App - Symfony + Panther
    <br>
</p>

## 📝 Table of Contents

- [About](#about)
- [Getting Started](#getting_started)
- [Deployment](#deployment)
- [Usage](#usage)
- [Built Using](#built_using)
- [TODO](../TODO.md)
- [Contributing](../CONTRIBUTING.md)
- [Authors](#authors)
- [Acknowledgments](#acknowledgement)

## 🧐 About <a name = "about"></a>

Simple scraper for not only (extensible) pricing Packages

## 🏁 Getting Started <a name = "getting_started"></a>

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

What things you need to install the software and how to install them:

```
"php": ">=7.2.5",
```

```
symfony CLI
```

<a name="<https://symfony.com/download>">https://symfony.com/download</a>

### Installing

A step by step series that tell you how to get a development env running.

After cloning run:

```
composer install
```

Install ChromeDriver and geckodriver locally

```
vendor/bin/bdi detect drivers
```

Fire the sccript:

```
symfony console app:crawl-website
```

There are 2 options available:

```
sortBy=<'ASC','DESC'>
website=..
```

New Website contract definition to be added under:

```
App\Scraper
```

## 🔧 Running the tests <a name = "tests"></a>

Unit tests:

```
./vendor/bin/phpunit
```

### and Stan

```
./vendor/bin/phpstan analyse src tests
```

## ⛏️ Built Using <a name = "built_using"></a>

- [Symfony](https://www.https://symfony.com/) - Kernel Framework
- [Panther](https://github.com/symfony/panther) - Panther

## ✍️ Authors <a name = "authors"></a>

- [@Innomatrix](https://github.com/innomatrix) - Idea & Initial work

## 🎉 Acknowledgements <a name = "acknowledgement"></a>

- Hat tip to anyone whose code was used
- Inspiration
- References
