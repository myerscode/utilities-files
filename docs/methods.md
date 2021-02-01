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

### exists `bool`

Does the file or directory actually exists

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->exits(); // true
$file->delete();
echo $file->exits(); // false
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

### namespace `string`

Get the namespace of the file (if it's a PHP file)

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->namespace();  // App
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

### touch `Utility`

Touch a file or directory, to ensure it exists

```php
$file = new Utility('./src/RandomClassForTestingWith.php');

echo $file->namespace();  // App
```
