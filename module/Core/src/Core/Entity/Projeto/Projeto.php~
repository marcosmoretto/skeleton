<?php

namespace Core\Entity\Projeto;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projeto
 *
 * @ORM\Table(name="projeto", indexes={@ORM\Index(name="IDX_A0559D945D37D0F1", columns={"id_status"}), @ORM\Index(name="IDX_A0559D94CF964237", columns={"id_prioridade"}), @ORM\Index(name="IDX_A0559D947BD815B7", columns={"id_dono"})})
 * @ORM\Entity
 */
class Projeto
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="projeto_id_seq", allocationSize=1, initialValue=1)
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", nullable=false)
     */
    public $nome;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_ini_estimado", type="datetime", nullable=false)
     */
    public $dataIniEstimado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_ini", type="datetime", nullable=false)
     */
    public $dataIni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_fim", type="datetime", nullable=true)
     */
    public $dataFim;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_fim_estimado", type="datetime", nullable=false)
     */
    public $dataFimEstimado;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descricao", type="string", nullable=true)
     */
    public $descricao;

    /**
     * @var \Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_status", referencedColumnName="id")
     * })
     */
    public $idStatus;

    /**
     * @var \Prioridade
     *
     * @ORM\ManyToOne(targetEntity="Prioridade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_prioridade", referencedColumnName="id")
     * })
     */
    public $idPrioridade;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_dono", referencedColumnName="id")
     * })
     */
    public $idDono;


}
