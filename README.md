<a name="readme-top"></a>

[![Contributors][contributors-shield]][contributors-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <h3 align="center">Telepath for Laravel</h3>

  <p align="center">
    Laravel integration for Telepath
    <br />
    <a href="https://telepath-php.dev/docs/getting-started/laravel"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/telepath-php/laravel/issues">Report Bug</a>
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li><a href="#about-the-project">About The Project</a></li>
    <li><a href="#getting-started">Getting Started</a></li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#known-limitations">Known Limitations</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

Telepath is a modern framework-agnostic libraray to create Telegram Bots in PHP.

This package integrates a Telepath based bot into your Laravel application being able to receive and respond to Telegram messages your bot receives.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

This package can be installed via composer:

`composer require telepath/laravel`

The package will automatically install the required dependencies to use Telepath in your Laravel application.

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- USAGE EXAMPLES -->
## Usage

You can get the Bot instance via the service container

```php
$bot = resolve(\Telepath\TelegramBot::class);
```

or by using autowiring to request an instance.

```php
public function __construct(
    protected TelegramBot $bot
) {}
```

_For a more detailled look including how to integrate Telepath into your Laravel application please also have a look into our [Documentation][docs]_

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- KNOWN LIMITATIONS -->
## Known Limitations

Please note that handling multple bots in the same Laravel application is not supported yet.

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE.md` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTACT -->
## Contact

Tii - [@TiiFuchs](https://twitter.com/TiiFuchs) - mail@tii.one

Project Link: [https://github.com/telepath-php/laravel](https://github.com/telepath-php/laravel)

<p align="right">(<a href="#readme-top">back to top</a>)</p>




<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/telepath-php/laravel.svg?style=for-the-badge
[contributors-url]: https://github.com/telepath-php/laravel/graphs/contributors
[stars-shield]: https://img.shields.io/github/stars/telepath-php/laravel.svg?style=for-the-badge
[stars-url]: https://github.com/telepath-php/laravel/stargazers
[issues-shield]: https://img.shields.io/github/issues/telepath-php/laravel.svg?style=for-the-badge
[issues-url]: https://github.com/telepath-php/laravel/issues
[license-shield]: https://img.shields.io/github/license/telepath-php/laravel.svg?style=for-the-badge
[license-url]: https://github.com/telepath-php/laravel/blob/master/LICENSE.txt

[docs]: https://telepath-php.dev
