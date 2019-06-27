<?php

namespace Core\Service\Gearman;

use Core\Service\Mail\Mail;
use Core\Service\Service;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use TweeGearmanStat\Queue\Gearman as QueueGearman;

/**
 * Serviço para tratar entidades Notificacao.
 *
 * @category Application
 */
class Gearman extends Service
{

    protected $em;
    protected $em_alternative;

    public function __construct(EntityManager $entityManager, EntityManager $entityManagerAlternative = null)
    {
        $this->setEntityManager($entityManager);
        $this->em = $entityManager;
        $this->em_alternative = $entityManagerAlternative;
    }

    const
        PRIORITY_LOW = 1,
        PRIORITY_NORMAL = 2,
        PRIORITY_HIGH = 3;

    /**
     * Objeto do tipo QueueGearman
     * @var QueueGearman
     */
    private $queueGearman = null;

    /**
     * Cria instancia de QueueGearman
     * @return QueueGearman
     */
    private function getQueueGearman()
    {
        if ($this->queueGearman == null) {
            $this->queueGearman = new QueueGearman(array(
                'workers' => array('host' => 'localhost', 'port' => 4730, 'timeout' => 1),
            ));
        }
        return $this->queueGearman;
    }

    /**
     * Manda executar serviço em background.
     *
     *
     * @throws Exception
     */
    public function execute($params)
    {
        $client = new \GearmanClient();
        $client->addServer();
        if (!isset($params['id'])) {
            $this->saveJob($params);
        } else {
            $this->updateJob($params['id']);
        }

        $workload = json_encode($params);

        switch ($params['priority']) {
            case self::PRIORITY_LOW:
                $client->doLowBackground($params['serverGearman'], $workload);
                break;
            case self::PRIORITY_HIGH:
                $client->doHighBackground($params['serverGearman'], $workload);
                break;
            default:
                $client->doBackground($params['serverGearman'], $workload);
                break;
        }

        if ($client->returnCode() != GEARMAN_SUCCESS) {
            $this->executeService($params);
            $mail = new Mail($this->em);
            $mail->gearmanError(['message' => $client->returnCode()]);
        }

        return $client->returnCode();
    }

    public function saveJob(&$params)
    {
        $workload = json_encode($params);
        $gearman = new \Core\Entity\Gearman\Gearman();
        $gearman->workload = $workload;
        $gearman->data = new \DateTime();
        $gearman->service = $params['service'];
        $this->em->persist($gearman);
        try {
            $this->em->flush();
        } catch (\Exception $e) {
        }
        $params['id'] = $gearman->id;
    }

    public function updateJob($id)
    {
        try {
            $this->em($this->em);
            $gearman = $this->em->find(\Core\Entity\Gearman\Gearman::class, $id);
            $gearman->data = new \DateTime();
            $this->em->persist($gearman);
            $this->em->flush();
        } catch (\Exception $e) {
        }
    }

    public function deleteJob($id)
    {
        try {
            $this->em($this->em);
            $job = $this->em->find(\Core\Entity\Gearman\Gearman::class, $id);
            $this->em->remove($job);
            $this->em->flush();
        } catch (\Exception $e) {
        }
    }

    /**
     * Executa serviço.
     *
     * @param array $params
     * @return array
     */
    public function executeService($params)
    {
        $result = array();
        if (isset($params['class']) && $params['service']) {
            try {
                $em = isset($params['em_alternative']) && $params['em_alternative'] === true ? 'em_alternative' : 'em';
                $this->emReset($this->$em);
                $service = new $params['class']($this->$em);
                $method = $params['service'];
                $result = $service->$method($params['parameters']);
                unset($service, $method);
            } catch (\Exception $e) {
                $mail = new Mail($this->em);
                $params['message'] = $e->getMessage();
                $mail->gearmanServiceExecuteError($params);
            }
            if (isset($params['id']))
                $this->deleteJob($params['id']);
        }

        return $result;
    }

    /**
     * Função que adiciona função na execução da worker.
     *
     * @param string $funcionName Nome da função
     */
    private function createServerFunction($functionName)
    {
        try {
            $maxLifeTime = new DateTime();
            $maxLifeTime->add(new DateInterval('PT10M'));

            $worker = new \GearmanWorker();
            $worker->addServer();
            $worker->setTimeout(450000);
            $worker->addFunction($functionName['functionName'], array($this, 'doBackground'));
            while ($worker->work()) {
                if ((new \DateTime()) > $maxLifeTime) {
                    exit;
                }
                if ($worker->returnCode() == GEARMAN_TIMEOUT) {
                    $mail = new Mail($this->em);
                    $mail->gearmanError(['message' => $worker->returnCode()]);
                    break;
                }

                if ($worker->returnCode() != GEARMAN_SUCCESS) {
                    $mail = new Mail($this->em);
                    $mail->gearmanError(['message' => $worker->returnCode()]);
                    break;
                }
            }
        } catch (Exception $e) {

        }

        return true;
    }

    /**
     * Deixa worker em estado de execução, essa worker deve rodar somente se existir tarefas na fila e por um período de 10 minutos.
     */
    public function startServer($functionName)
    {

        return $this->createServerFunction($functionName);
    }

    /**
     * Verifica se tem job na fila para ser executado.
     *
     * @param String $service
     *
     * @return bool
     */
    public function hasJobQueue($service)
    {
        $workers = $this->getQueueGearman()->status();
        foreach ($workers['workers'] as $work) {
            if ($work['name'] == $service && $work['queue'] > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Serviço responsável por startar workers.
     *
     * @return array
     */
    public function checkWorkers()
    {
        $workers = $this->em->getRepository(\Core\Entity\Gearman\GearmanServices::class)->findAll();

        foreach ($workers as $worker) {
            if ($this->needRunWork($worker->servico, $worker->processos_paralelos)) {
                $this->startServer([
                    'functionName' => $worker->servico,
                ]);
            }
        }

        return $workers;
    }

    private function needRunWork($name, $quantidadeProcessosParalelos)
    {
        $workers = $this->getQueueGearman()->status();
        foreach ($workers['workers'] as $work) {
            if ($work['name'] == $name) {
                return ($work['workers'] < $quantidadeProcessosParalelos);
            }
        }
        return false;
    }

    /**
     * Serviço executado pelo gearman em background.
     *
     * @param \GearmanJob $job
     *
     * @return type
     */
    public function doBackground(\GearmanJob $job)
    {
        $params = json_decode($job->workload(), true);

        try {
            $this->executeService($params);
        } catch (\Exception $e) {
        }

        return true;
    }

    public function checkJobs()
    {
        $workers = $this->getQueueGearman()->status();
        $activeWorkers = [];
        foreach ($workers['workers'] as $work) {
            $activeWorkers[] = $work['name'];
            if ($work['queue'] === '0') {
                $this->reexecuteJobs($this->findNoExecutedJobs($work['name']));
            }
        }
        $this->reexecuteJobs($this->findNoExecutedJobs($activeWorkers));
    }

    public function reexecuteJobs($jobs)
    {
        foreach ($jobs as $job) {
            try {
                $workload = json_decode($job->workload, true);
                $workload['id'] = $job->id;
                $this->execute($workload);
            } catch (\Exception $e) {
            }

        }
    }

    public function findNoExecutedJobs($service)
    {
        $date = new DateTime("10 minutes ago");

        if (is_array($service)) {
            $service = "'" . implode("','", $service) . "'";
            $qb = $this->em->createQueryBuilder()
                ->select('Gearman')
                ->from(\Core\Entity\Gearman\Gearman::class, 'Gearman')
                ->where("Gearman.service NOT IN ({$service}) AND Gearman.data < ?1")
                ->setParameters([
                    1 => $date->format('Y-m-d H:i:s')
                ]);
        } else {
            $qb = $this->em->createQueryBuilder()
                ->select('Gearman')
                ->from(\Core\Entity\Gearman\Gearman::class, 'Gearman')
                ->where("Gearman.service = ?1 AND Gearman.data < ?2")
                ->setParameters([
                    1 => $service,
                    2 => $date->format('Y-m-d H:i:s')
                ]);
        }

        return $qb->getQuery()->getResult();
    }
}