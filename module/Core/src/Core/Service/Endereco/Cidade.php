<?php

namespace Core\Service\Endereco;

use Doctrine\ORM\EntityManager;
use Core\Service\Service;

class Cidade extends Service
{
    protected $em;
    protected $nome = 'Cidade';
    protected $entitypath = 'Core\Entity\Endereco\Cidade';
    protected $entityname = 'Cidade';
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
        if($params){

            return $this->em->getRepository($this->entitypath)->findBy(['id_estado' => $params['id_estado']]);
        }
        return $this->findGenericAll(
            $params, $this->entitypath, $this->entityname, $this->nome . 's',
            $this->entityname . $this->columnorder, $this->order
        );
    }
}