<?php
namespace User\V1\Rest\Device;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Core\Resource\AbstractResource;
use Core\Service\Transportes\Device as Service;

class DeviceResource extends AbstractResource
{

    public function __construct($services)
    {
        $this->service = new Service($services->get('Doctrine\ORM\EntityManager'));
        parent::__construct($services, $this->service);
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
