<?php

namespace Core\Entity\Gearman;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Acls
 *
 * @ORM\Table(name="public.gearman")
 * @ORM\Entity
 */
class Gearman
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="gearman_id_seq", allocationSize=1, initialValue=1)
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="workload", type="string", nullable=false)
     */
    public $workload;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", nullable=false)
     */
    public $service;

    /**
     * @var DateTimeType
     *
     * @ORM\Column(name="data", type="datetime", nullable=false)
     */
    public $data;
}