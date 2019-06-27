<?php

namespace Core\Entity\Acl;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acls
 *
 * @ORM\Table(name="acl_usuarios")
 * @ORM\Entity
 */
class AclUsuarios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="acl_usuarios_id_usuario_seq", allocationSize=1, initialValue=1)
     */
    public $id_usuario;

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
     * @var boolean
     *
     * @ORM\Column(name="dev", type="boolean", nullable=false)
     */
    public $dev;

    /**
     * @var string
     *
     * @ORM\Column(name="client_id", type="string", nullable=false)
     */
    public $client_id;

    public function getArrayCopy()
    {
        $this->criado->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
        $this->modificado->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
        return array(
            'id_usuario' => $this->id_usuario,
            'client_id' => $this->client_id,
            'nome' => $this->nome,
            'dev' => $this->dev,
            'criado' => $this->criado->format('d/m/Y H:i'),
            'modificado' => $this->modificado->format('d/m/Y H:i'),
        );
    }
}