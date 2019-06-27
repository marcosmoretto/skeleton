<?php

namespace Core\Entity\Acl;

use Doctrine\ORM\Mapping as ORM;

/**
 * DmUsuario
 *
 * @ORM\Table(name="oauth_clients", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_9b3d98e7927c74", columns={"email"})}, indexes={@ORM\Index(name="idx_9b3d987b00651c", columns={"status"})})
 * @ORM\Entity
 */
class OauthClients
{
    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="oauth_clients_client_id_seq", allocationSize=1, initialValue=1)
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="client_secret", type="string", length=80, nullable=false)
     */
    public $senha;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_uri", type="string", length=2000, nullable=false)
     */
    public $redirectUri;

    /**
     * @var string
     *
     * @ORM\Column(name="grant_types", type="string", length=80, nullable=true)
     */
    public $grantTypes;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string", length=2000, nullable=false)
     */
    public $scope;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=255, nullable=false)
     */
    public $userId;

}

