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
     * @var string
     *
     * @ORM\Column(name="data_fim_estimado", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dataFimEstimado;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="tarefa", type="string", nullable=false)
     */
    private $tarefa;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", nullable=false)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_ini", type="datetime", nullable=false)
     */
    private $dataIni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_ini_estimado", type="datetime", nullable=false)
     */
    private $dataIniEstimado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_fim", type="datetime", nullable=false)
     */
    private $dataFim;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_fim_estimado_1", type="datetime", nullable=false)
     */
    private $dataFimEstimado1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tempo", type="datetime", nullable=false)
     */
    private $tempo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tempo_estimado", type="datetime", nullable=false)
     */
    private $tempoEstimado;

    /**
     * @var \Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_status", referencedColumnName="id")
     * })
     */
    private $idStatus;

    /**
     * @var \Prioridade
     *
     * @ORM\ManyToOne(targetEntity="Prioridade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_prioridade", referencedColumnName="id")
     * })
     */
    private $idPrioridade;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_criador", referencedColumnName="id")
     * })
     */
    private $idCriador;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_desenvolvedor", referencedColumnName="id")
     * })
     */
    private $idDesenvolvedor;

    /**
     * @var \Projeto
     *
     * @ORM\ManyToOne(targetEntity="Projeto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projeto", referencedColumnName="id")
     * })
     */
    private $idProjeto;


}
