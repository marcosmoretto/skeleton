<?php
namespace Projeto\V1\Rest\Tarefa;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Core\Service\Projeto\TarefaService as Tarefa;

class TarefaResource extends AbstractResourceListener
{
    protected $em;
	protected $sm;
	protected $db;
	//protected $service;
	public function __construct($services){
		$this->sm = $services;
		$this->em = $services->get('Doctrine\ORM\EntityManager');
		//$this->db = $service->get('oauth2');
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $data = $this->getInputFilter()->getValues();
        $usr = $this->getEvent()->getIdentity()->getAuthenticationIdentity();
        $tarefa = new Tarefa($this->em);
        $retorno = $tarefa->create($data, $usr);
        return new ApiProblem($retorno['codigo'], $retorno['mensagem']);
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $usr = $this->getEvent()->getIdentity()->getAuthenticationIdentity();
        $tarefa = new Tarefa($this->em);
        $retorno = $tarefa->delete($id, $usr);
        return new ApiProblem($retorno['codigo'], $retorno['mensagem']);
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $tarefa = new Tarefa($this->em);
        return $tarefa->fetch(ltrim($id, '='));
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        $tarefa = new Tarefa($this->em);
        return $tarefa->fetch();
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patchList($data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        $usr = $this->getEvent()->getIdentity()->getAuthenticationIdentity();
        $data = $this->getInputFilter()->getValues();
        $tarefa = new Tarefa($this->em);
        $tarefa->update($id, $data, $usr);
    }
}
