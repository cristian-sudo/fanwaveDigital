<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Webmozart\Assert\Assert;

#[ORM\Entity]
class League
{
    public const STRING_MAX_LENGTH = 255;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    #[Groups('read_league')]
    private int $idRemote;

    #[ORM\Column(type: 'string', length: self::STRING_MAX_LENGTH)]
    #[Groups('read_league')]
    private string $name;

    #[ORM\Column(type: 'string', length: self::STRING_MAX_LENGTH)]
    #[Groups('read_league')]
    private string $type;

    #[ORM\Column(type: 'string', length: self::STRING_MAX_LENGTH)]
    #[Groups('read_league')]
    private string $logo;

    #[ORM\Column(type: 'string', length: self::STRING_MAX_LENGTH)]
    #[Groups('read_league')]
    private string $country;

    #[ORM\Column(type: 'string', length: self::STRING_MAX_LENGTH)]
    #[Groups('read_league')]
    private string $flag;

    public function __construct(int $idRemote, string $name, string $type, string $logo, string $country, string $flag)
    {
        $this->idRemote = $idRemote;
        $this->name = $name;
        $this->type = $type;
        $this->logo = $logo;
        $this->country = $country;
        $this->flag = $flag;
    }

    public function getIdRemote(): int
    {
        return $this->idRemote;
    }

    public function setIdRemote(int $idRemote): void
    {
        $this->idRemote = $idRemote;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getFlag(): string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): void
    {
        $this->flag = $flag;
    }

    public function getRequiredId(): int
    {
        Assert::notNull($this->id);

        return $this->id;
    }
}
