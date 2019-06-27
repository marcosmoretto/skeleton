<?php

namespace Core\Entity\Acl;

use Doctrine\ORM\Mapping as ORM;

/**
 * OauthAcessTokens
 *
 * @ORM\Table(name="oauth_access_tokens")
 * @ORM\Entity
 */
class OauthAccessTokens {
    /**
     *
     * @var string
     * @ORM\Column(name="access_token", type="string", nullable=false)
     * @ORM\Id
//     * @ORM\GeneratedValue
     */
    public $access_token;

    /**
     *
     * @var string @ORM\Column(name="client_id", type="string", length=255, nullable=false)
     */
    public $client_id;

    /**
     *
     * @var string @ORM\Column(name="user_id", type="string", length=255, nullable=true)
     */
    public $user_id;

    /**
     *
     * @var \DateTime @ORM\Column(name="expires", type="datetime", nullable=false)
     */
    public $expires;

    /**
     *
     * @var string @ORM\Column(name="scope", type="string", length=2000, nullable=true)
     */
    public $scope;

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param string $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param \DateTime $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }


}

