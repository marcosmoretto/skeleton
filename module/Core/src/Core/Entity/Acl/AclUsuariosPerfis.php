<?php

namespace Core\Entity\Acl;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acls
 *
 * @ORM\Table(name="acl_usuarios_perfis")
 * @ORM\Entity
 */
class AclUsuariosPerfis
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuarios_perfis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="acl_usuarios_perfis_id_usuarios_perfis_seq", allocationSize=1, initialValue=1)
     */
    public $id_usuarios_perfis;
    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AclUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id_tela")
     * })
     */
    public $id_usuario;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AclPerfis")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_perfil", referencedColumnName="id_perfil")
     * })
     */
    public $id_perfil;

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

    public function getArrayCopy()
    {
        $this->criado->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
        $this->modificado->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
        return array(
            'id_tela' => $this->id_tela,
            'id_item' => $this->id_item,
            'criado' => $this->criado->format('d/m/Y H:i'),
            'modificado' => $this->modificado->format('d/m/Y H:i'),
        );
    }
}