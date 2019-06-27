<?php

namespace Core\Service;

use Doctrine\ORM\EntityManager;

class TransportesLogs extends Service
{
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->em = $entityManager;
    }

    public function queryLogs($empresa)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('Log')->from(\Core\Entity\Logs\TransportesLog::class, 'Log')
            ->orderBy('Log.id_log', 'DESC');
//        if (isset($empresa)) {
//            $qb->where('Log.id_empresa = ?1');
//            $qb->setParameter(1, $empresa);
//        }

        return $qb;
    }

    public function getLogs($data, $empresa = null)
    {
        $select = $this->queryLogs($empresa);

        if ($data['search']['value'] != '') {
            $select->andWhere('(UPPER(Log.conteudo) like :search)');
            $select->setParameter('search', '%' . mb_strtoupper($data['search']['value'], 'UTF-8') . '%', \PDO::PARAM_STR);
            $campoBusca = true;
        }

        $columns = $data['columns'];

        $colunas = [
            'id_empresa' => 'Log.id_empresa',
            'conteudo' => 'Log.conteudo',
        ];
        if ($data['order']) {
            foreach ($data['order'] as $order) {
                $select->addOrderBy($colunas[$columns[$order['column']]['data']], $order['dir']);
            }
        }
        return $this->paginacao($data, $select, $colunas);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function createLog($data)
    {
        $id_empresa = isset($data['empresa']) ? $data['empresa'] : 1;
        try {
            $empresa = $this->em->find(\Core\Entity\Logs\TransportesLog::class, $id_empresa);
        } catch (\Exception $e) {
            throw new \Exception('Empresa não localizada.', 404);
        }

        $tipo_log = $this->em->getRepository(\Core\Entity\Logs\TransportesLog::class)
            ->findOneBy(['descricao' => $data['tipo']]);
        if (!$tipo_log) {
            $tipo_log = new \Core\Entity\Logs\TransportesLog();
            $tipo_log->descricao = $data['tipo'];
            $this->em->persist($tipo_log);
        }
        $log = new \Core\Entity\Logs\TransportesLog();
        $log->conteudo = $data['conteudo'];
        $log->id_empresa = $empresa;
        $log->id_tipo_log = $tipo_log;
        $log->data = new \DateTime();
        try {
            $this->em->persist($log);
            $this->em->flush();

            return $log;
        } catch (\Exception $e) {

            throw new \Exception('Erro ao criar log.', 500);
        }
    }
}