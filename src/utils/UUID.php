<?php
namespace App\utils;

use Ramsey\Uuid\Uuid as RamseyUuid;

class UUID {
	public static function generate(): string {
		return RamseyUuid::uuid4()->toString();
	}
}