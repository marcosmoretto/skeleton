<?php
namespace User\V1\Rest\Device;

class DeviceResourceFactory
{
    public function __invoke($services)
    {
        return new DeviceResource($services);
    }
}
