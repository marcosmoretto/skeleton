<?php

namespace Core\Service\Endereco;

use Doctrine\ORM\EntityManager;
use Core\Service\Service;

class Estado extends Service
{
    protected $em;
    protected $nome = 'Estado';
    protected $entitypath = 'Core\Entity\Endereco\Estado';
    protected $entityname = 'Estado';
    protected $columnorder = '.descricao';
    protected $order = 'ASC';

    public function __construct(EntityManager $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->em = $entityManager;
    }

    public function createEsp($data)
    {
        try {
            $this->getDependencies($data, $this->entitypath);
        } catch (\Exception $e) {
            throw new \Exception('Dados inválidos', 502);
        }

        return $this->create($data, new $this->entitypath(), $this->nome);
    }


    public function getEsp($data)
    {

       return $this->get($data, $this->entitypath, $this->nome);
    }

    public function findAll($params = [])
    {

        return $this->findGenericAll(
            $params, $this->entitypath, $this->entityname, $this->nome . 's',
            $this->entityname . $this->columnorder, $this->order
        );
    }
}