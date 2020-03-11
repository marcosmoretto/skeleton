<?php

namespace Core\Entity\Projeto;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prioridade
 *
 * @ORM\Table(name="prioridade")
 * @ORM\Entity
 */
class Prioridade
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="prioridade_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", nullable=false)
     */
    private $nome;


}
