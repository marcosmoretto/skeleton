<?php

namespace Core\Service\Projeto;

use Core\Entity\Projeto\Tarefa;
use Doctrine\ORM\EntityManager;

class TarefaService {
    public $em;
    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function update($id, $data, $usr){
        $usuario = $this->em->getRepository(\Core\Entity\Projeto\Usuario::class)->findOneBy(['clientId' => $usr['client_id']]);
        $usuario = $usuario->id;
        $tarefa = $this->em->getRepository(\Core\Entity\Projeto\Tarefa::class)->findOneBy(['id' => $id, 'idCriador' => $usuario]);
        if(!$tarefa){
            $tarefa = $this->em->getRepository(\Core\Entity\Projeto\Tarefa::class)->findOneBy(['id' => $id, 'idDesenvolvedor' => $usuario]);
        }
        // \Doctrine\Common\Util\Debug::dump($projeto);
        if($tarefa){
            return $this->create($data, $usr, $tarefa);
        }
        return 'Nenhuma tarefa encontrada!';
    }

    public function create($data, $usr, $tarefa=null){
        if(!$tarefa){
            $tarefa = new Tarefa();
        }
        $tarefa->nome = $data['nome'];
        $tarefa->data = new \DateTime();
    	$data_fim_estimado = \DateTime::createFromFormat("d/m/Y", $data['data_fim_estimado']);
    	$data_ini_estimado = \DateTime::createFromFormat("d/m/Y", $data['data_ini_estimado']);
    	$data_fim = \DateTime::createFromFormat("d/m/Y", $data['data_fim']);
    	$data_ini = \DateTime::createFromFormat("d/m/Y", $data['data_ini']);
    	$tarefa->dataIniEstimado = $data_fim_estimado;
    	$tarefa->dataIni = $data_ini;
    	$tarefa->dataFimEstimado = $data_fim_estimado;
        $tarefa->dataFim = $data_fim;
        $projeto = $this->em->getRepository(\Core\Entity\Projeto\Projeto::class)->findOneBy(['id' => $data['id_projeto']]);
        if(!$projeto){
            return ['codigo' => 404, 'mensagem' => 'Nenhum projeto foi encontrado!'];
        }
        // \Doctrine\Common\Util\Debug::dump($projeto);
        // exit;
        $tarefa->idProjeto = $projeto;
		$status = $this->em->getRepository(\Core\Entity\Projeto\Status::class)->findOneBy(['id' => $data['id_status']]);
        $tarefa->idStatus = $status;
        $prioridade = $this->em->getRepository(\Core\Entity\Projeto\Prioridade::class)->findOneBy(['id' => $data['id_prioridade']]);
        $tarefa->idPrioridade = $prioridade;
        $tarefa->tarefa = $data['tarefa'];
        $tarefa->tempoEstimado = $data['tempo_estimado'];
		$dono = $this->em->getRepository(\Core\Entity\Projeto\Usuario::class)->findOneBy(['clientId' => $usr['client_id']]);
		//\Doctrine\Common\Util\Debug::dump($dono);
        $tarefa->idCriador = $dono;
        $dev = $this->em->getRepository(\Core\Entity\Projeto\Usuario::class)->findOneBy(['id' => $data['id_desenvolvedor']]);
		$tarefa->idDesenvolvedor = $dev;
    	try{
    		$this->em->persist($tarefa);
    		$this->em->flush();
            return ['codigo' => 201, 'mensagem' => 'Tarefa criada com sucesso!'];
		} catch (\Exception $e){
			// var_dump($e->getCode());
			// var_dump($e->getMessage());
			return ['codigo' => 500, 'mensagem' => 'Não foi possível criar uma tarefa!'];
		}
    }

    public function fetch($id=null){
        
        $qb = $this->em->createQueryBuilder()
            ->select('t.id, t.nome, t.tarefa, t.dataIni, t.dataFim')
            ->from('Core\Entity\Projeto\Tarefa','t');
        if($id){
            $qb->where("t.id = ?1");
            // $qb->setParamaters(array(1 => $id));
            $qb->setParameters([1 => $id]);
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function delete($id, $usr){
        $usuario = $this->em->getRepository(\Core\Entity\Projeto\Usuario::class)->findOneBy(['clientId' => $usr['client_id']]);
        // $dono = $this->em->getRepository(\Core\Entity\Projeto\Tarefa::class)->findOneBy(['idCriador' => $usuario->id]);
        // if($dono){
            $sql = "delete from tarefa where id = {$id} and id_criador = {$usuario->id} returning id";
            $stmt = $this->em->getConnection()->prepare($sql);
            try {
                $stmt->execute();
                $retorno = $stmt->fetchAll();
                if(!isset($retorno[0])){
                    return ['codigo' => 404, 'mensagem' => 'Não foi possível excluir a tarefa, verique se você é o Dono!'];
                }
            } catch (\Exception $e){
                return ['codigo' => 500, 'mensagem' => 'Não foi possível excluir a tarefa!'];
            }
            return ['codigo' => 200, 'mensagem' => 'Excluído com sucesso!'];
        // }
        // return ['codigo' => 403, 'mensagem' => 'Você não é o dono da tarefa, deste modo não poderá excluí-la!'];
    }

}