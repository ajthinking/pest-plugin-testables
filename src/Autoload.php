<?php

declare(strict_types=1);

namespace Ajthinking\PestPluginTestables;

use Exception;

function testable(mixed $testee): Testable
{
	if(is_string($testee)) {
		try {
			// If we are in a Laravel context attempt resolving
			$testee = call_user_func('app')->make($testee);
		} catch(Exception $_) {}
	}

    return new Testable($testee);
}