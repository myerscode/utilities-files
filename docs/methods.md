# Available Methods


### className `string`

Get the assumed PSR-4 class name for the file

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->className();
// RandomClassForTestingWith
```

### appendContent `Utility`

If the path is a file and if it exists, appends content to the end of the file 

```php
$file = new Utility('./logfile.txt');

echo $file->appendContent('hello world');
// ... hello world
```

### content `string`

If the path is a file and if it exists, gets the content of the file

```php
$file = new Utility('./logfile.txt');

echo $file->content();
// ...
```

### delete `Utility`

Delete the file or directory with the given path

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

$file->delete();
```

### directory `Utility`

Get the directory of the path

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

$file->directory(); // ./src

$file = new Utility('./src/App/Commands');

$file->directory(); // ./src/App/Commands
```

### exists `bool`

Does the file or directory actually exists

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->exists(); // true
$file->delete();
echo $file->exists(); // false
```

### extension `string`

Guess the path's file extension

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->extension(); // php

// get with dot prefix
echo $file->extension(true); // .php
```

### files `array`

Get a collection of spl file objects, from within the path if it's a directory

```php
$file = new Utility('./src/');

echo $file->files();  // [...$srcFiles]
```

### fullyQualifiedClassname `string`

Get the fully qualified class name from the file (if it's a PHP file)

```php
$file = new Utility('./src/');

echo $file->fullyQualifiedClassname();  // App/RandomClassForTestingWith
```

### hasAbsolutePath `bool`

Does the current path have an absolute file path

```php
$file = new Utility('/src');

echo $file->hasAbsolutePath();  // true

$file = new Utility('~/src');

echo $file->hasAbsolutePath();  // false
```

### isDirectory `bool`

Checks if the path is a directory

```php
$file = new Utility('./src/');

echo $file->isDirectory();  // true

$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->isDirectory();  // false
```

### isFile `bool`

Checks if the path is a file

```php
$file = new Utility('./src/');

echo $file->isFile();  // false

$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->isFile();  // true
```

### makeDirectory `Utility`

Create directory if it doesn't already exist

```php
$file = new Utility('~/Code/app');

echo $file->makeDirectory();
```

### namespace `string`

Get the namespace of the file (if it's a PHP file). The file is tokenised, so braced namespaces, tabs, CRLF line endings and commented-out declarations are handled. Throws `FileFormatExpection` when no namespace is declared.

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->namespace();  // App
```

### name `string`

Get the file or directory name, optionally with its extension

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->name(); // RandomClassForTestingWith

// get with file extension
echo $file->name(true); // RandomClassForTestingWith.php
```

### path `string`

Get the path of the file

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->path();  // ./src/RandomClassForTestingWith.php
```

### setContent `Utility`

If the path is a file and if it exists, sets the content of the file.

The write is atomic: content goes to a temporary file in the same directory, which is then renamed over the original, so readers never see a partial file. Permissions are preserved and symlinks are written through. `appendContent` writes in place under an exclusive lock.

```php
$file = new Utility('./logfile.txt');

echo $file->setContent('hello world');
// hello world
```

### touchFile `Utility`

Touch a file to, ensure it exists

```php
$file = new Utility('~/Code/app.php');

echo $file->touchFile();
```

### touch `Utility`

Touch a file or directory, to ensure it exists.

If the path doesn't see a file extension, a directory is assumed to be wanted.

```php
$file = new Utility('~/Code/app.php');

echo $file->touch(); // file created

$file = new Utility('~/Code/app');

echo $file->touch(); // directory created
```

## Security

- Pass a `$root` as the second constructor argument (`new Utility($path, $root)`) to confine mutating operations (`delete`, `setContent`, `appendContent`, `touch`, `touchFile`, `makeDirectory`). Paths that resolve outside `$root` are refused. Without a `$root`, no confinement applies, so never build paths from untrusted input without one.
- Stream wrapper paths such as `phar://`, `php://`, `data:` and `http://` are rejected with an `InvalidArgumentException`. Only plain paths and `file:` are accepted.
