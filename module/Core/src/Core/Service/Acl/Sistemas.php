<?php

namespace Core\Service\Acl;

use Doctrine\ORM\EntityManager;
use Core\Service\Service;

class Sistemas extends Service
{
    protected $em;
    protected $nome = 'Sistema';
    protected $entitypath = 'Core\Entity\Acl\AclSistemas';
    protected $entityname = 'AclSistemas';
    protected $columnorder = '.nome';
    protected $order = 'ASC';
    protected $client_id;

    public function __construct(EntityManager $entityManager, $client_id)
    {
        $this->setEntityManager($entityManager);
        $this->em = $entityManager;
        $this->client_id = $client_id;
    }

    public function createEsp($data)
    {
        if ($this->isDev($this->client_id)) {

            return ['codigo' => 403, 'mensagem' => 'Usuário sem permissão!'];
        }

        $data['modificado'] = new \DateTime();
        $data['criado'] = $data['modificado'];

        return $this->create($data, new \Core\Entity\Acl\AclSistemas(), $this->nome);
    }

    public function removeEspList($data)
    {
        if ($this->isDev($this->client_id)) {

            return ['codigo' => 403, 'mensagem' => 'Usuário sem permissão!'];
        }

        return $this->removeList($data, $this->entitypath, $this->nome . 's', $this->client_id);
    }

    public function removeEsp($data)
    {
        if ($this->isDev($this->client_id)) {

            return ['codigo' => 403, 'mensagem' => 'Usuário sem permissão!'];
        }

        return $this->remove($data, $this->entitypath, $this->nome, $this->client_id);
    }

    public function getEsp($data)
    {

        return $this->get($data, $this->entitypath, $this->nome, $this->client_id);
    }

    public function findAll($params = [])
    {

        return $this->findGenericAll(
            $params, $this->entitypath, $this->entityname, $this->nome,
            $this->entityname . $this->columnorder, $this->order, $this->client_id
        );
    }

    public function updateEspList($data)
    {
        if ($this->isDev($this->client_id)) {

            return ['codigo' => 403, 'mensagem' => 'Usuário sem permissão!'];
        }

        return $this->updateList($data, $this->entitypath, $this->nome . 's', $this->client_id);
    }

    public function updateEsp($data, $id)
    {
        if ($this->isDev($this->client_id)) {

            return ['codigo' => 403, 'mensagem' => 'Usuário sem permissão!'];
        }

        if (is_object($data))
            $data = json_decode(json_encode($data), True);
        $data['modificado'] = new \DateTime('now');

        return $this->update($data, $id, $this->entitypath, $this->nome, $this->client_id);
    }

    public function getSistemaUsuario(){
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('AclSistemas')
            ->distinct()
            ->from(\Core\Entity\Acl\AclSistemas::class, 'AclSistemas')
            ->join(\Core\Entity\Acl\AclProgramas::class, 'AclProgramas')
            ->join(\Core\Entity\Acl\AclUsuarios::class, 'AclUsuarios')
            ->join(\Core\Entity\Acl\AclTelasPerfis::class, 'AclTelasPerfis');
        $qb->where('AclUsuarios.client_id = ?1');
        $qb->setParameter(1, $this->client_id);

        return $qb->getQuery()->getResult();
    }
}