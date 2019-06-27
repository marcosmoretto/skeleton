<?php

namespace Core\Entity\Acl;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acls
 *
 * @ORM\Table(name="acl_perfis")
 * @ORM\Entity
 */
class AclPerfis
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_perfil", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="acl_perfis_id_perfil_seq", allocationSize=1, initialValue=1)
     */
    public $id_perfil;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AclSistemas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sistema", referencedColumnName="id_sistema")
     * })
     */
    public $id_sistema;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modificado", type="datetime", nullable=false)
     */
    public $modificado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="criado", type="datetime", nullable=false)
     */
    public $criado;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", nullable=false)
     */
    public $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", nullable=false)
     */
    public $descricao;

    public function getArrayCopy()
    {
        $this->criado->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
        $this->modificado->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
        return array(
            'id_perfil' => $this->id_perfil,
            'descricao' => $this->descricao,
            'nome' => $this->nome,
            'criado' => $this->criado->format('d/m/Y H:i'),
            'modificado' => $this->modificado->format('d/m/Y H:i'),
        );
    }
}