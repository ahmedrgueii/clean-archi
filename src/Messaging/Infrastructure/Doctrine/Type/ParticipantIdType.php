<?php

declare(strict_types=1);

namespace App\Messaging\Infrastructure\Doctrine\Type;

use App\Messaging\Domain\ValueObject\ParticipantId;
use App\Common\Infrastructure\Doctrine\Type\UuidType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ParticipantIdType extends UuidType
{
    public const TYPE = 'participant_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): ParticipantId
    {
        return ParticipantId::fromString((string) $value);
    }
}
