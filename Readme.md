![License](https://img.shields.io/github/license/stukenov/nova-tiptap)
![Latest release](https://img.shields.io/github/v/release/stukenov/nova-tiptap?sort=semver)

# Laravel Nova Tiptap Editor Field

A modern, extensible WYSIWYG rich-text field for [Laravel Nova](https://nova.laravel.com), powered by the [Tiptap editor](https://github.com/ueberdosis/tiptap) by [@ueberdosis](https://github.com/ueberdosis). Pick exactly the buttons you need, drop in image and file uploads, and ship clean, predictable HTML (or JSON) straight to your database.

- **Configurable toolbar** — enable only the buttons your editors need, grouped with separators and line breaks.
- **Image & file uploads** — upload to any configured disk and path, or link by URL.
- **Clean HTML output** — semantic markup you control, with an optional raw-HTML editing mode and syntax highlighting.
- **Save as JSON** — persist the editor document as JSON instead of HTML when you need structured content.
- **Tables, headings, alignment, RTL, placeholders and more** — a full toolbox that themes alongside the rest of Nova.

## Installation

Install the package into a Laravel Nova application via Composer:

```bash
composer require stukenov/nova-tiptap
```

The field's service provider is auto-discovered, so there is no further setup.

## Usage

Add the field to any Nova resource. With no configuration you get a minimal toolbar (bold and italic):

```php
use STukenov\Tiptap\Tiptap;

public function fields(NovaRequest $request)
{
    return [
        // ...
        Tiptap::make('Body'),
    ];
}
```

### Choosing your buttons

Use `buttons()` to define the toolbar. Use `|` to insert a vertical separator and `br` to force a line break in the toolbar:

```php
use STukenov\Tiptap\Tiptap;

Tiptap::make('Body')
    ->buttons([
        'heading',
        '|',
        'italic',
        'bold',
        '|',
        'link',
        'code',
        'strike',
        'underline',
        'highlight',
        '|',
        'bulletList',
        'orderedList',
        'br',
        'codeBlock',
        'blockquote',
        '|',
        'horizontalRule',
        'hardBreak',
        '|',
        'table',
        '|',
        'image',
        '|',
        'textAlign',
        '|',
        'rtl',
        '|',
        'history',
        '|',
        'editHtml',
    ])
    ->headingLevels([2, 3, 4]);
```

## Configuration options

The field exposes a fluent API. All methods return `$this`, so they can be chained.

| Method | Description |
| --- | --- |
| `buttons(array $buttons)` | The toolbar buttons to display. Use `'|'` for a separator and `'br'` for a line break. |
| `headingLevels($levels)` | Heading levels available for the `heading` button. Pass an array like `[2, 3, 4]`, or an integer `n` to enable H1–H`n`. Default is H1–H3. |
| `syntaxHighlighting()` | Enable syntax highlighting for `codeBlock`. |
| `htmlTheme($theme)` | CodeMirror theme used by the `editHtml` raw-HTML editor. Defaults to `material`. See the [CodeMirror themes](https://codemirror.net/demo/theme.html). |
| `alignments($alignments)` | Alignments available for `textAlign` (e.g. `['left', 'right', 'center', 'justify']`). |
| `defaultAlignment($alignment)` | Default alignment when using `textAlign`. |
| `alignElements($elements)` | DOM elements that may be aligned when using `textAlign`. |
| `placeholder($placeholder)` | Placeholder text shown in the empty editor. |
| `imageSettings($settings)` | Disk/path for image uploads, e.g. `['disk' => 'public', 'path' => 'images', 'withFileUpload' => true]`. |
| `fileSettings($settings)` | Disk/path for files uploaded via the `link` button. |
| `linkSettings($settings)` | Link behaviour, e.g. `['withFileUpload' => false]` to hide file uploads on links. |
| `placeholderBlocks($blocks)` | Define re-usable placeholder content blocks. |
| `saveAsJson()` | Persist the editor content as a JSON document instead of HTML. |

This field uses Nova's `Expandable` trait, so it also supports Nova's expandable-field behaviour for controlling whether the editor is shown expanded by default.

### Available buttons

The following button keys are supported in `buttons()`:

`heading`, `italic`, `bold`, `link`, `code`, `codeBlock`, `strike`, `underline`, `highlight`, `bulletList`, `orderedList`, `blockquote`, `horizontalRule`, `hardBreak`, `table`, `image`, `textAlign`, `rtl`, `history`, `editHtml`, plus the toolbar helpers `|` (separator) and `br` (line break).

### Headings and `headingLevels`

Passing the `'heading'` button gives you H1, H2 and H3 by default. Override the available levels with `headingLevels([2, 3, 4])` (or `headingLevels(4)` for H1–H4).

### Links, `linkSettings` and `fileSettings`

The `'link'` button lets editors link text to a URL (with an optional "open in new window") and to a file uploaded to the server. Control whether file upload is offered:

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'link'])
    ->linkSettings([
        'withFileUpload' => false,
    ]);
```

Configure where linked files are stored with `fileSettings`:

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'link'])
    ->fileSettings([
        'disk' => 'your_custom_disk',
        'path' => 'your/custom/path',
    ]);
```

If no disk is set, the field uses the `public` disk when one is defined in `config/filesystems.php`, otherwise `config('filesystems.default')`. If no path is set, it uses the root of that disk.

### Images and `imageSettings`

The `'image'` button lets editors add images from a file upload or a URL. Configure storage with `imageSettings`:

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'image'])
    ->imageSettings([
        'disk' => 'your_custom_disk',
        'path' => 'your/custom/path',
    ]);
```

The same disk/path defaults apply as for files. To disallow file uploads and only accept URLs, set `withFileUpload`:

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'image'])
    ->imageSettings([
        'withFileUpload' => false,
    ]);
```

### Text alignment with `textAlign`

Adding `'textAlign'` provides buttons to align text **left**, **right**, **center** and **justify** (default **left**). Customise the available alignments and the default:

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'textAlign'])
    ->alignments(['right', 'left'])
    ->defaultAlignment('right');
```

### RTL support with `rtl`

Adding `'rtl'` provides a button that toggles RTL mode (`dir="rtl"`) on the selected block elements:

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'rtl']);
```

### The two "code" buttons

`'code'` produces inline code (`<code></code>`), while `'codeBlock'` produces a fenced block (`<pre><code></code></pre>`).

### Syntax highlighting with `codeBlock`

Turn on syntax highlighting for `codeBlock`:

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'code', 'codeBlock'])
    ->syntaxHighlighting();
```

### Edit HTML

The `'editHtml'` button lets editors toggle the editor into a textarea to edit the raw HTML directly. Set the CodeMirror theme used by that view with `htmlTheme()` (default `material`):

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'code', 'editHtml'])
    ->htmlTheme('night');
```

### Save as JSON

Persist the editor's content as a JSON document instead of HTML:

```php
Tiptap::make('Body')
    ->buttons(['italic', 'bold', 'code'])
    ->saveAsJson();
```

### Visibility in the index view

Like Nova's `Textarea` and `Trix` fields, this field is hidden from index views. Surface its content there with a [computed field](https://nova.laravel.com/docs/resources/fields.html#computed-fields).

## Screenshots

The editor themes alongside the rest of Nova.

The Tiptap editor with all buttons enabled:

![The Tiptap editor with all buttons](readme-images/all-buttons.png)

The same editor restyled to match a custom Nova theme:

![The Tiptap editor with a custom theme](readme-images/stripe-theme.png)

> Replace these screenshots with captures from your own application to show the editor in context.

## Related Laravel Nova packages

- [nova-ckeditor5-classic](https://github.com/stukenov/nova-ckeditor5-classic) — a CKEditor 5 Classic field for Laravel Nova.
- [nova-media-field](https://github.com/stukenov/nova-media-field) — a media management field for Laravel Nova.

## Licence

The MIT License (MIT). Please see the [License File](LICENCE) for more information.
