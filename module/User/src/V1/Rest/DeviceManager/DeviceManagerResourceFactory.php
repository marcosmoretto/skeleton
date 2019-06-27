<?php
namespace User\V1\Rest\DeviceManager;

class DeviceManagerResourceFactory
{
    public function __invoke($services)
    {
        return new DeviceManagerResource($services);
    }
}
