<?php
namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class GenderType extends AbstractEnumType
{
    public const MALE = "M";
    public const FEMALE = "F";

    protected static array $choices = [
        self::MALE => "Homme",
        self::FEMALE => "Femme",
    ];
}
