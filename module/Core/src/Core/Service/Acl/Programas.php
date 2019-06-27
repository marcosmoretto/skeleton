<?php

namespace Core\Service\Acl;

use Doctrine\ORM\EntityManager;
use Core\Service\Service;

class Programas extends Service
{
    protected $em;
    protected $nome = 'Programa';
    protected $entitypath = 'Core\Entity\Acl\AclProgramas';
    protected $entityname = 'AclProgramas';
    protected $columnorder = '.nome';
    protected $order = 'ASC';
    protected $sistema;
    protected $client_id;

    public function __construct(EntityManager $entityManager, $client_id)
    {
        $this->setEntityManager($entityManager);
        $this->em = $entityManager;
        $this->client_id = $client_id;
    }

    protected function getDependencies(&$data){
        $this->sistema = $this->em->find('Core\Entity\Acl\AclSistemas', $data['id_sistema']);
        if(!$this->sistema) {
            $data['id_sistema'] = $this->sistema;
        }
    }

    public function createEsp($data)
    {
        if ($this->isDev($this->client_id)) {

            return ['codigo' => 403, 'mensagem' => 'Usuário sem permissão!'];
        }

        $this->getDependencies($data);
        if(!$this->sistema){
            return ['codigo' => 404, 'mensagem' => 'Sistema não encontrado'];
        }
        $data['modificado'] = new \DateTime();
        $data['criado'] = $data['modificado'];

        return $this->create($data, new \Core\Entity\Acl\AclProgramas(),$this->nome);
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
            $params, $this->entitypath, $this->entityname, $this->nome.'s',
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

        $this->getDependencies($data);
        if (is_object($data))
            $data = json_decode(json_encode($data), True);
        $data['modificado'] = new \DateTime('now');

        return $this->update($data, $id, $this->entitypath, $this->nome, $this->client_id);
    }

    public function getProgramaUsuario()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('AclProgramas')
            ->from(\Core\Entity\Acl\AclProgramas::class, 'AclProgramas')
            ->join(\Core\Entity\Acl\AclPerfis::class, 'AclPerfis')
            ->join(\Core\Entity\Acl\AclUsuarios::class, 'AclUsuarios')
            ->join(\Core\Entity\Acl\AclUsuariosPerfis::class, 'AclUsuariosPerfis')
            ->join(\Core\Entity\Acl\AclTelasPerfis::class, 'AclTelasPerfis');
        $qb->where('AclUsuarios.client_id = ?1');
        $qb->setParameter(1, $this->client_id);

        return $qb->getQuery()->getResult();
    }
}