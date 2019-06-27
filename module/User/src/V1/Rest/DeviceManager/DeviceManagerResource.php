<?php
namespace User\V1\Rest\DeviceManager;


use ZF\ApiProblem\ApiProblem;
use Core\Resource\AbstractResource;
use Core\Service\Transportes\DeviceManager as Service;

class DeviceManagerResource extends AbstractResource
{

    public function __construct($services)
    {
        $this->service = new Service($services->get('Doctrine\ORM\EntityManager'));
        parent::__construct($services, $this->service);
    }

    public function create($params = []) {
        $email = $this->getClientId();
        
        try {
            if (isset($params->player_id)) {
                $params->email = $email;
                $this->service->createEsp($params);
            } else {
                throw new \Exception("Player ID não informado!", 204);
            }   
        } catch (\Exception $e) {
            return new ApiProblem($e->getCode(), $e->getMessage());
        }
    }

    
    public function fetchAll($params = [])
    {
        return $this->service->findAll();
    }
    
    public function deleteList($params = []) {
        try{
            $this->service->removeListByParams($params);
            return new ApiProblem(200, 'Vínculo de dispositivo removido');
        }catch (\Exception $e){
            return new ApiProblem($e->getCode(), $e->getMessage());
        }
    }
}
