<?php

namespace Core\Entity\Gearman;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Acls
 *
 * @ORM\Table(name="public.gearman_services")
 * @ORM\Entity
 */
class GearmanServices
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="gearman_services_id_seq", allocationSize=1, initialValue=1)
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="servico", type="string", nullable=false)
     */
    public $servico;

    /**
     * @var integer
     *
     * @ORM\Column(name="processos_paralelos", type="integer", nullable=false)
     */
    public $processos_paralelos;
}