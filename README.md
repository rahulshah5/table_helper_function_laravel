# 🔍 Laravel `table()` Helper  
A clean and modern replacement for `dd()` when working with tabular data in Laravel. This custom helper formats your arrays, collections, and paginated data into a searchable, toggleable HTML table — right in your browser.

---

## 🚀 Features

- ✅ **Searchable**: Instantly find what you need from large datasets.  
- ✅ **Toggleable Columns**: Show or hide columns on the fly.  
- ✅ **Nested Data Support**: Automatically flattens and visualizes nested arrays/objects.  
- ✅ **Modern UI**: Minimal, intuitive, and dev-friendly interface.  
- ✅ **Works with Collections, Arrays & Pagination**.

---

## 📦 Installation

Simply copy the function from the table.php file in the repository:  

Place it inside your `app/helpers.php` file or any custom helper file you load in `composer.json`:

```php
// app/helpers.php

if (!function_exists('table')) {
    function table($data)
    {
        // [Paste the function here]
    }
}
```

Don’t forget to load the helper in your `composer.json` if not already:

```json
"autoload": {
    "files": [
        "app/helpers.php"
    ]
}
```

Then run:

```bash
composer dump-autoload
```

---

## 🧑‍💻 Usage

Use `table()` instead of `dd()` or `dump()` when you want to visualize tabular data:

```php
$data = User::with('roles')->paginate(10);
table($data);
```

Supported formats:
- Laravel collections
- Eloquent models
- Paginated data
- Arrays of arrays or objects

---

## 🖼 Example

```php
table(User::all());
```

Output:  
An HTML table with columns auto-generated from the data structure, including nested relationships flattened with dot notation (e.g., `roles.0.name`).

---

## 🧠 Why Use This?

Laravel's `dd()` is powerful, but often unreadable with large or nested data. `table()` brings clarity to your debugging workflow, especially when working with:

- API responses  
- Collections of Eloquent models  
- Nested relationship data  
- Paginated results

---

## 📣 Feedback & Contributions

Have suggestions, improvements, or want to contribute? Feel free to fork or drop your ideas via issues or pull requests.

---

## 📄 License

Open-sourced under the [MIT license](LICENSE).

---

## 🙌 Credits

Crafted with ❤️ to make debugging elegant.  
Inspired by the need for clarity while working on real-world Laravel projects.

---

Let me know if you'd like to include screenshots, badges, or package it for Packagist!