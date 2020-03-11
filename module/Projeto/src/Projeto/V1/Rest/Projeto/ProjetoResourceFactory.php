<?php
namespace Projeto\V1\Rest\Projeto;

class ProjetoResourceFactory
{
    public function __invoke($services)
    {
        return new ProjetoResource($services);
    }
}
