<?php

namespace Core\Entity\Projeto;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participante
 *
 * @ORM\Table(name="participante", indexes={@ORM\Index(name="IDX_85BDC5C3FCF8192D", columns={"id_usuario"}), @ORM\Index(name="IDX_85BDC5C37EC834E4", columns={"id_projeto"})})
 * @ORM\Entity
 */
class Participante
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="participante_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_limite", type="date", nullable=false)
     */
    private $dataLimite;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $idUsuario;

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
