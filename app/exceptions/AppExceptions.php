<?php

namespace App\Exceptions;

use Nette;


/**
 * Class DuplicateEntryException
 * @package App\Exceptions
 */
class DuplicateEntryException extends \Exception
{
	// try to insert duplicate value to fields with unique key
}

/**
 * Class AccessDeniedException
 * @package App\Exceptions
 */
class AccessDeniedException extends \Exception
{
	// user have not premission to view or do something
}

/**
 * Class InvalidArgumentException
 * @package App\Exceptions
 */
class InvalidArgumentException extends \Exception
{
	// parameter suplied as an argument is wrong (typehint/range)
}

/**
 * Class ConfirmationEmailException
 * @package App\Exceptions
 */
class ConfirmationEmailException extends \Exception
{
	// something goes wrong with confirmation emails. (Ie. users acount is active, but email is not confirmed.)
}

/**
 * Class GeneralException
 * @package App\Exceptions
 */
class GeneralException extends \Exception
{
	// something goes wrong but we don't know what.
}





