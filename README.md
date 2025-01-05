# Xevmium - Modern PHP Website Builder

🚀 A lightweight, elegant PHP framework for building modern websites with minimal setup.

## ✨ Features

- 🎨 Built-in theme system with 15 beautiful color schemes
- 📱 Fully responsive design out of the box
- 📝 Markdown support for content creation
- 🔒 Built-in XSS protection
- 🧩 Component-based architecture
- 📦 Zero external dependencies
- 🎯 Simple API for rapid development

## 🔧 Installation

Install via Composer:

```bash
composer require kornineqnetwork/xevmium
```

## 🚀 Quick Start

```php
<?php
require_once 'vendor/autoload.php';

use kornineqnetwork\Xevmium\Run;

$app = new Run();
$app->register();

// Build a simple page
$app->buildSite(
    pageTitle: 'My Website',
    fileTitle: 'Home',
    themeName: 'blue',
    navItems: [
        ['url' => '/', 'title' => 'Home'],
        ['url' => '/about', 'title' => 'About']
    ],
    content: '<h1>Welcome to my website!</h1>',
    copyright: 'Your Name'
);
```

## 🎨 Available Themes

- blue (default)
- green
- purple
- orange
- red
- teal
- pink
- yellow
- gray
- indigo
- cyan
- amber
- lime
- brown
- deepPurple

## 📖 Examples

### Creating a Button
```php
$xevmium->createButton('Click Me', '/destination', 'custom-class');
```

### Adding a Table
```php
$headers = ['Name', 'Age', 'Location'];
$rows = [
    ['John Doe', '25', 'New York'],
    ['Jane Smith', '30', 'London']
];
$xevmium->addTable($headers, $rows);
```

### Using Markdown
```php
$markdownParser = new MarkdownParser();
$markdownParser->loadMarkdownPage('content.md');
```

## 🛠️ Advanced Usage

### Custom Theme Implementation
```php
$app = new Run();
$app->register();
$app->settheme('purple');

$xevmium = $app->build('My Website', 'Custom Page');
$xevmium->buildHeader();
$xevmium->center('start');
$xevmium->createButton('Learn More', '#', 'custom-btn');
$xevmium->center('stop');
$xevmium->buildFooter('Your Company');
```

## 📝 License

MIT License. See [LICENSE](LICENSE) for more information.

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/KornineQNetwork/Xevmium/issues).

## 🌟 Show your support

Give a ⭐️ if this project helped you!
