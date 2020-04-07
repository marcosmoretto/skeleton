<?php
namespace Projeto\V1\Rest\Tarefa;

class TarefaResourceFactory
{
    public function __invoke($services)
    {
        return new TarefaResource($services);
    }
}
