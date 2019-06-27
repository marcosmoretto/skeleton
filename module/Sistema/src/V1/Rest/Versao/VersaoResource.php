<?php
namespace Sistema\V1\Rest\Versao;


class VersaoResource extends AbstractResourceListener
{
    protected $em;
    protected $sm;
    protected $db;
    protected $service;

    public function __construct($services, $service)
    {
        $this->sm = $services;
        $this->em = $services->get('Doctrine\ORM\EntityManager');
        $this->db = $services->get('oauth2');
        $this->service = $service;
        if (isApp()) {
            $this->refreshToken(clientToken(), 20, 'd');
        }
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */

    private $versao = '0.0030';

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        echo '{"versao": '.$this->versao.'}';
        exit;
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        echo '{"versao": '.$this->versao.'}';
        exit;
    }
}
