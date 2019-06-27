<?php
namespace Sistema\V1\Rest\Versao;

class VersaoResourceFactory
{
    public function __invoke($services)
    {
        return new VersaoResource($services);
    }
}
