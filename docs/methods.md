# Available Methods


### className `string`

Get the assumed PRS4 class name for the file

### content `appendContent`

If the path is a file and if it exists, appends content to the end of the file 

```php
$file = new Utility('./logfile.txt');

echo $file->appendContent('hello world');
// ... hello world
```

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->className();
// RandomClassForTestingWith
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

echo $file->exits(); // true
$file->delete();
echo $file->exits(); // false
```

### extension `string`

Does the file or directory actually exists

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->extension(); // php

// get with dot prefix
echo $file->exits(true); // .php
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

echo $file->isDirectory();  // false

$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->isDirectory();  // true
```

### makeDirectory `Utility`

Create directory if it doesn't already exist

```php
$file = new Utility('~/Code/app');

echo $file->makeDirectory();
```

### namespace `string`

Get the namespace of the file (if it's a PHP file)

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->namespace();  // App
```

### name `string`

Does the file or directory actually exists

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

### content `setContent`

If the path is a file and if it exists, sets the content of the file

```php
$file = new Utility('./logfile.txt');

echo $file->appendContent('hello world');
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
