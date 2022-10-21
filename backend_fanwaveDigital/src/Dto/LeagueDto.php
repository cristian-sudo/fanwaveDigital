<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\League;
use Symfony\Component\Validator\Constraints as Assert;

class LeagueDto
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private mixed $id_remote = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: League::STRING_MAX_LENGTH)]
    private mixed $name = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: League::STRING_MAX_LENGTH)]
    private mixed $type = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: League::STRING_MAX_LENGTH)]
    private mixed $logo = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: League::STRING_MAX_LENGTH)]
    private mixed $country = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: League::STRING_MAX_LENGTH)]
    private mixed $flag = null;

    /**
     * @param array<array-key, string> $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $property => $value) {
            $property = (string) $property;

            if (property_exists(self::class, $property)) {
                /* @phpstan-ignore-next-line */
                $this->{$property} = $value;
            }
        }
    }

    /**
     * @return mixed|null
     */
    public function getIdRemote(): mixed
    {
        return $this->id_remote;
    }

    /**
     * @return mixed|null
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getFlag(): string
    {
        return $this->flag;
    }
}
