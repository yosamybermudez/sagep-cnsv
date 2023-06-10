<?php

namespace App\Repository;

use App\Entity\CambiarContrasenaPeticion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

/**
 * @method CambiarContrasenaPeticion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CambiarContrasenaPeticion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CambiarContrasenaPeticion[]    findAll()
 * @method CambiarContrasenaPeticion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CambiarContrasenaPeticionRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CambiarContrasenaPeticion::class);
    }

    public function createResetPasswordRequest(
        object $user,
        \DateTimeInterface $expiresAt,
        string $selector,
        string $hashedToken
    ): ResetPasswordRequestInterface {
        return new CambiarContrasenaPeticion(
            $user,
            $expiresAt,
            $selector,
            $hashedToken
        );
    }
}
