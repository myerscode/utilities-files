<?php

namespace Myerscode\Utilities\Files\Exceptions;

use InvalidArgumentException;

/**
 * @deprecated misspelt, catch FileFormatException instead. Kept as the parent of FileFormatException so
 *             existing `catch (FileFormatExpection)` blocks still catch what is thrown.
 */
class FileFormatExpection extends InvalidArgumentException
{
}
