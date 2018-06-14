<?php

namespace App\Entity;

use App\Model\CreatePlayerDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="kingdom")
 * @ORM\Entity(repositoryClass="App\Repository\KingdomRepository")
 */
class Kingdom
{
    const POPULATION_STARTER_NUMBER = 3000;

    const POWER_STARTER_NUMBER = 3000;

    const GOLD_STARTER_NUMBER = 5000;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="population", type="integer")
     */
    private $population = self::POPULATION_STARTER_NUMBER;

    /**
     * @var int|null
     *
     * @ORM\Column(name="power", type="integer")
     */
    private $power = self::POWER_STARTER_NUMBER;

    /**
     * @var int
     *
     * @ORM\Column(name="gold", type="integer")
     */
    private $gold = self::GOLD_STARTER_NUMBER;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\KingdomBuilding", mappedBy="kingdom")
     */
    private $kingdomBuildings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\KingdomResource",
     *     mappedBy="kingdom",
     *     fetch="EXTRA_LAZY"
     * )
     */
    private $kingdomResources;

    public function __construct()
    {
        $this->kingdomResources = new ArrayCollection();
        $this->kingdomBuildings = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getPopulation(): ?int
    {
        return $this->population;
    }

    /**
     * @param int|null $population
     */
    public function setPopulation(?int $population): void
    {
        $this->population = $population;
    }

    /**
     * @return int|null
     */
    public function getPower(): ?int
    {
        return $this->power;
    }

    /**
     * @param int|null $power
     */
    public function setPower(?int $power): void
    {
        $this->power = $power;
    }

    /**
     * @return int
     */
    public function getGold(): int
    {
        return $this->gold;
    }

    /**
     * @param int $gold
     */
    public function setGold(int $gold): void
    {
        $this->gold = $gold;
    }

    /**
     * @return Region
     */
    public function getRegion(): Region
    {
        return $this->region;
    }

    /**
     * @param Region $region
     */
    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }

    /**
     * Add kingdomBuilding
     *
     * @param KingdomBuilding $kingdomBuilding
     *
     * @return Kingdom
     */
    public function addKingdomBuilding(KingdomBuilding $kingdomBuilding)
    {
        $this->kingdomBuildings[] = $kingdomBuilding;

        $kingdomBuilding->setKingdom($this);

        return $this;
    }

    /**
     * Remove kingdomBuilding
     *
     * @param KingdomBuilding $kingdomBuilding
     */
    public function removeKingdomBuilding(KingdomBuilding $kingdomBuilding)
    {
        $this->kingdomBuildings->removeElement($kingdomBuilding);

        $kingdomBuilding->setKingdom(null);
    }

    /**
     * Get kingdomBuildings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKingdomBuildings()
    {
        return $this->kingdomBuildings;
    }

    public static function initKingdom(CreatePlayerDTO $createPlayerDTO): self
    {
        $kingdom = new self();
        $kingdom->name = $createPlayerDTO->getUsername();
        $kingdom->description = 'Aucune Description';
        $kingdom->region = $createPlayerDTO->getRegion();

        return $kingdom;
    }

    public function getKingdomResources(): Collection
    {
        return $this->kingdomResources;
    }

    public function getKingdomResource(Resource $resource): ?KingdomResource
    {
        $filteredKingdomResources = $this->kingdomResources->filter(function (KingdomResource $kingdomResource) use ($resource) {
            return $kingdomResource->getResource() === $resource;
        });

        if ($filteredKingdomResources->isEmpty()) {
            return null;
        }

        return $filteredKingdomResources->first();
    }
}
