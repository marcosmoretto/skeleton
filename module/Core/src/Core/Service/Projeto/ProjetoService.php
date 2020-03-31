<?php

namespace Core\Service\Projeto;

use Core\Entity\Projeto\Projeto;
use Doctrine\ORM\EntityManager;

class ProjetoService {
    public $em;
    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function create($data, $usr){
    	$projeto = new Projeto();
    	$projeto->nome = $data['nome'];
    	$data_ini = \DateTime::createFromFormat("Y-m-d", $data['data_inicial']);
    	$data_fim = \DateTime::createFromFormat("Y-m-d", $data['data_fim']);
    	$projeto->dataIniEstimado = $data_ini;
    	$projeto->dataIni = $data_ini;
    	$projeto->dataFimEstimado = $data_fim;
    	$projeto->dataFim = $data_fim;
		$projeto->descricao = $data['descricao'];
		$dono = $this->em->getRepository(\Core\Entity\Projeto\Usuario::class)->findOneBy(['clientId' => $usr['client_id']]);
		//\Doctrine\Common\Util\Debug::dump($dono);
		$projeto->idDono = $dono;
		$status = $this->em->getRepository(\Core\Entity\Projeto\Status::class)->findOneBy(['id' => $data['status']]);
		$projeto->idStatus = $status;
		$prioridade = $this->em->getRepository(\Core\Entity\Projeto\Prioridade::class)->findOneBy(['id' => $data['prioridade']]);
		$projeto->idPrioridade = $prioridade;
    	try{
    		$this->em->persist($projeto);
    		$this->em->flush();
            return $projeto;
		} catch (\Exception $e){
			var_dump($e->getCode());
			var_dump($e->getMessage());
			exit;
		}
    }

    public function fetch($id=null){
        $qb = $this->em->createQueryBuilder()
            ->select('p.id, p.nome, p.dataIni, p.dataFim')
            ->from('Core\Entity\Projeto\Projeto','p');
        if($id){
            $qb->where("p.id = ?1");
            // $qb->setParamaters(array(1 => $id));
            $qb->setParameters([1 => $id]);
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

}