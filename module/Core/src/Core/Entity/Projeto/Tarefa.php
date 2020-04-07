<?php

namespace Core\Entity\Projeto;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tarefa
 *
 * @ORM\Table(name="tarefa", indexes={@ORM\Index(name="IDX_31B4CBA5D37D0F1", columns={"id_status"}), @ORM\Index(name="IDX_31B4CBACF964237", columns={"id_prioridade"}), @ORM\Index(name="IDX_31B4CBABB4D0D62", columns={"id_criador"}), @ORM\Index(name="IDX_31B4CBAA65E027", columns={"id_desenvolvedor"}), @ORM\Index(name="IDX_31B4CBA7EC834E4", columns={"id_projeto"})})
 * @ORM\Entity
 */
class Tarefa
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tarefa_id_seq", allocationSize=1, initialValue=1)
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", nullable=false)
     */
    public $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="tarefa", type="string", nullable=false)
     */
    public $tarefa;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", nullable=false)
     */
    public $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_ini", type="datetime", nullable=false)
     */
    public $dataIni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_ini_estimado", type="datetime", nullable=false)
     */
    public $dataIniEstimado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_fim", type="datetime", nullable=false)
     */
    public $dataFim;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_fim_estimado", type="datetime", nullable=false)
     */
    public $dataFimEstimado;

    /**
     * @var int
     *
     * @ORM\Column(name="tempo", type="integer", nullable=false)
     */
    public $tempo;

    /**
     * @var int
     *
     * @ORM\Column(name="tempo_estimado", type="integer", nullable=false)
     */
    public $tempoEstimado;

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
     *   @ORM\JoinColumn(name="id_criador", referencedColumnName="id")
     * })
     */
    public $idCriador;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_desenvolvedor", referencedColumnName="id")
     * })
     */
    public $idDesenvolvedor;

    /**
     * @var \Projeto
     *
     * @ORM\ManyToOne(targetEntity="Projeto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projeto", referencedColumnName="id")
     * })
     */
    public $idProjeto;


}
