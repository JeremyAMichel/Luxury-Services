<?php
namespace App\Services;

use App\Attribute\ProfileField;
use App\Entity\Candidate;
use App\Interfaces\ProfileProgressCalculatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

class ProfileProgressCalculator implements ProfileProgressCalculatorInterface
{
    private static array $profileMappingCache = [];
    private EntityManagerInterface $entityManager;
    private ReflectionClass $reflection;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculerProgress(Candidate $candidate): int
    {
        $className = get_class($candidate);
        $this->reflection = new ReflectionClass($candidate);

        // Vérifier si le mapping des propriétés annotées par ProfileField est déjà en cache
        if (!isset(self::$profileMappingCache[$className])) {
            self::$profileMappingCache[$className] = $this->getProfileMapping();
        }

        // Récupérer le mapping des propriétés annotées par ProfileField
        $mapping = self::$profileMappingCache[$className];
        $completedFields = 0;
        $totalFields = count($mapping);

        // Parcourir les propriétés annotées par ProfileField
        foreach ($mapping as $propertyName) {
            $propertyValue = $this->getPropertyValue($candidate, $propertyName);

            // Vérifier si la propriété est complétée
            if ($this->isFieldCompleted($propertyValue)) {
                $completedFields++;
            }
        }

        // Eviter la division par zéro
        if ($totalFields === 0) {
            return 0;
        }

        // Calculer le pourcentage de complétion et l'arrondir
        $progressPercentage = ($completedFields / $totalFields) * 100;
        $progressPercentage = (int) round($progressPercentage);

        // Mettre à jour le pourcentage de complétion du candidat
        $candidate->setCompletionPercentage($progressPercentage);
        $this->entityManager->persist($candidate);
        $this->entityManager->flush();

        return $progressPercentage;
    }

    /**
     * Récupérer le mapping des propriétés annotées par ProfileField
     */
    private function getProfileMapping(): array
    {
        $mapping = [];
        // Parcourir les propriétés de la classe Candidate
        foreach ($this->reflection->getProperties() as $property) {
            if ($this->isProfileField($property)) {
                $mapping[] = $property->getName();
            }
        }
        return $mapping;
    }

    private function getPropertyValue(Candidate $candidate, string $propertyName)
    {
        // Utilisez des méthodes getter dynamique pour accéder aux valeurs des propriétés
        $getter = 'get' . ucfirst($propertyName);
        if (method_exists($candidate, $getter)) {
            return $candidate->$getter();
        }
        return null;
    }

    private function isFieldCompleted($value): bool
    {
        if ($value === null) {
            return false;
        }
        if (is_string($value) && trim($value) === '') {
            return false;
        }
        if (is_array($value) && empty($value)) {
            return false;
        }
        if ($value instanceof \Countable && count($value) === 0) {
            return false;
        }
        return true;
    }

    private function isProfileField(\ReflectionProperty $property): bool
    {
        $attributes = $property->getAttributes(ProfileField::class);
        return !empty($attributes);
    }
}