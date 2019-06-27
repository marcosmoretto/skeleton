<?php

namespace Core\Service;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Core\Entity\Acl\OauthAccessTokens;
use Doctrine\ORM\EntityManager;
use Zend\Db\Sql\Ddl\Column\Datetime;
use ZF\Apigility\Documentation\Api;
use ZF\ApiProblem\ApiProblem;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Aqilix\ORM\Mapper\AbstractMapper;
use Aqilix\ORM\Mapper\MapperInterface;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class Service extends AbstractMapper implements MapperInterface
{
    const
        INT = 'int',
        STRING = 'string',
        BOOLEAN = 'boolean',
        DATE = 'date';
    /**
     * @var ServiceManager
     */
    protected $serviceManager;
    protected $em;

    public function __construct(EntityManager $em = null)
    {
        $this->em = $em;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        //return $this;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceManager()
    {

        return $this->serviceManager;
    }

    /**
     * Retrieve TableGateway
     *
     * @param  string $table
     * @return TableGateway
     */
    protected function getTable($table)
    {
        $sm = $this->getServiceManager();
        $dbAdapter = $sm->get('DbAdapter');
        $tableGateway = new TableGateway($dbAdapter, $table, new $table);
        $tableGateway->initialize();

        return $tableGateway;
    }

    /**
     * Retrieve EntityManager
     *
     * @return Doctrine\ORM\EntityManager
     */
    public function getObjectManager()
    {
        $objectManager = $this->getService('Doctrine\ORM\EntityManager');

        return $objectManager;
    }

    /**
     * Retrieve EntityManager
     *
     * @return Doctrine\ORM\EntityManager
     */
    public function getObjectManagerFinanceiro()
    {

        return $this->getService('doctrine.entitymanager.orm_alternative');
    }

    /**
     * Retrieve Service
     *
     * @return Service
     */
    protected function getService($service)
    {

        return $this->getServiceManager()->get($service);
    }

    /**
     *
     * @param type $data
     * @param type $indexValue
     * @param type $indexDescription
     * @return array Array contendo $indexDescription
     */
    public function comboFormat($data, $indexValue, $indexDescription)
    {
        $combo = array();
        foreach ($data as $d) {
            $combo[$d[$indexValue]] = $d[$indexDescription];
        }

        return $combo;
    }

    /**
     *
     * @return string
     */
    protected function getRole()
    {
        $role = $this->getService('Session')->offsetGet('role');

        return $role;
    }

    /**
     *
     * @param string $data
     * @return \DateTime com a data informada
     */
    protected function objetoData($data)
    {
        $format = 'd/m/Y';

        return \DateTime::createFromFormat($format, $data);
    }

    /**
     *
     * @param string $data
     * @return number
     */
    protected function formatNumeric($data)
    {

        return (double)str_replace(',', '.', str_replace('.', '', $data));
    }

    /**
     *
     * @param string $client_id
     * @return boolean
     */
    public function isDev($client_id)
    {
        $result = $this->getEntityManager()->getRepository('Core\Entity\Acl\AclUsuarios')->findOneBy(['client_id' => $client_id]);

        return $result->dev;
    }

    public function isAdm($client_id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('AclUsuarios.id_usuario')
            ->from('Core\Entity\Acl\AclUsuarios', 'AclUsuarios')
            ->join('Core\Entity\Acl\AclUsuariosPerfis', 'AclUsuariosPerfis', 'WITH', 'AclUsuariosPerfis.id_usuario = AclUsuarios.id_usuario')
            ->join('Core\Entity\Acl\AclPerfis', 'AclPerfis', 'WITH', 'AclPerfis.id_perfil = AclUsuariosPerfis.id_id_perfil')
            ->where('AclPerfis.nome LIKE ?1 AND AclUsuarios.client_id = ?2')
            ->setParameter(['1' => '%ADMINISTRADOR%', '2' => $client_id]);
        $result = $qb->getQuery()->getResult();
        if (isset($result['id_usuario'])) {

            return true;
        }

        return false;
    }

    /**
     *
     * @param array $event =  $this->getEvent()->getIdentity()->getAuthenticationIdentity();
     * @return mixed
     */
    public function checkRefreshToken($data, $event, $verificarPermissao = false)
    {
        $em = $this->getEntityManager();
        $token = $em->getRepository('Core\Model\OauthAccessTokens')->findOneBy(array('access_token' => $event['access_token'], 'client_id' => $event['client_id']));
        if ($data) {
            $datahoje = new \DateTime('now');
            $usuario = $em->getRepository('Core\Model\DmUsuario')->findOneBy(array('email' => $data['login_email']));
            if (isset($data['empresa'])) {
                $emp = $em->find('Core\Model\ErpCadEmpresa', $data['empresa']);
                @$empresa = $em->getRepository('Core\Model\SisUsuarioEmpresa')->findOneBy(array('usuario' => $usuario, 'empresa' => $emp));
                if (!$empresa) {

                    return 'Empresa não encontrada!';
                }
            }
        }
        if (!$token) {

            return 'Você não possui permissão!';
        }
        if ($verificarPermissao) {
            $result = false;
            if ($verificarPermissao['origem'] == 'clientes') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('Pessoa.id')
                    ->from('Core\Model\ErpCadPessoaEmpresaCliente', 'Cliente')
                    ->join('Core\Model\ErpCadPessoa', 'Pessoa', 'WITH', 'Cliente.idPessoa = Pessoa.id')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Cliente.idEmpresa')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND Pessoa.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'fornecedores') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('Pessoa.id')
                    ->from('Core\Model\ErpCadPessoaEmpresaFornecedor', 'Fornecedor')
                    ->join('Core\Model\ErpCadPessoa', 'Pessoa', 'WITH', 'Fornecedor.idPessoa = Pessoa.id')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Fornecedor.idEmpresa')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND Pessoa.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'transportadoras') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('Pessoa.id')
                    ->from('Core\Model\ErpCadPessoaEmpresaTransportadora', 'Transportadora')
                    ->join('Core\Model\ErpCadPessoa', 'Pessoa', 'WITH', 'Transportadora.idPessoa = Pessoa.id')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Transportadora.idEmpresa')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND Transportadora.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'vendas') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('Venda.id')
                    ->from('Core\Model\ErpVenVenda', 'Venda')
                    ->join('Core\Model\ErpCadEmpresa', 'Empresa', 'WITH', 'Empresa.id = Venda.idEmpresa')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Empresa.id')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND Venda.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'empresas') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('Empresa.id')
                    ->from('Core\Model\ErpCadEmpresa', 'Empresa')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Empresa.id')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND Empresa.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'compras') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('Compra.id')
                    ->from('Core\Model\ErpComCompra', 'Compra')
                    ->join('Core\Model\ErpCadEmpresa', 'Empresa', 'WITH', 'Empresa.id = Compra.idEmpresa')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Empresa.id')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND Compra.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'prodserv') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('Produto.id')
                    ->from('Core\Model\ErpEstProdutoServico', 'Produto')
                    ->join('Core\Model\ErpCadEmpresa', 'Empresa', 'WITH', 'Empresa.id = Produto.idEmpresa')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Empresa.id')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND Produto.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'contabanco') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('ContaBancaria.id')
                    ->from('Core\Model\ErpFinBancoEmpresa', 'ContaBancaria')
                    ->join('Core\Model\ErpCadEmpresa', 'Empresa', 'WITH', 'Empresa.id = ContaBancaria.empresa')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Empresa.id')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND ContaBancaria.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'centrocustos') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('CentroCustos.id')
                    ->from('Core\Model\ErpFinCentroCustos', 'CentroCustos')
                    ->join('Core\Model\ErpCadEmpresa', 'Empresa', 'WITH', 'Empresa.id = CentroCustos.idEmpresa')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Empresa.id')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND CentroCustos.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            } elseif ($verificarPermissao['origem'] == 'titulo') {
                $qb = $this->getEntityManager()->createQueryBuilder()
                    ->select('Titulo.id')
                    ->from('Core\Model\ErpFinTituloRecPag', 'RecPag')
                    ->join('Core\Model\ErpFinTitulo', 'Titulo', 'WITH', 'Titulo.id = RecPag.idTitulo')
                    ->join('Core\Model\ErpCadEmpresa', 'Empresa', 'WITH', 'Empresa.id = Titulo.idEmpresa')
                    ->join('Core\Model\SisUsuarioEmpresa', 'SisUsuario', 'WITH', 'SisUsuario.empresa = Empresa.id')
                    ->join('Core\Model\DmUsuario', 'Usuario', 'WITH', 'SisUsuario.usuario = Usuario.id')
                    ->where('Usuario.email = ?1 AND RecPag.id = ?2')
                    ->setParameters(array('1' => $event['client_id'], '2' => $verificarPermissao['id']));
                $result = $qb->getQuery()->getResult();
            }
            if (!$result) {

                return 'Você não possui permissão!';
            }
        }
        $minutes_to_add = 500;
        $expira = $token->getExpires();
        $datahora = new \DateTime('now');
        $datahora = $datahora->format('Y-m-d H:i:s');
//        $datahora = $expira->format('Y-m-d H:i:s');
        $horaNova = strtotime("$datahora + $minutes_to_add minutes");
        $horaFormatada = new \Datetime(date("Y-m-d H:i:s", $horaNova));
        $token->setExpires($horaFormatada);
        $em->persist($token);
        try {
            $em->flush();

            return false;
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    protected function verificaUsuario($maillogin, $empresa = null)
    {
        //verificando usuario
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('Usuario')
            ->from(\Core\Entity\Transportes\Usuario::class, 'Usuario')
            ->where('Usuario.email = ?1')
            ->setParameters(array(1 => $maillogin));
        $result = $qb->getQuery()->getResult();
        if (!$result) {

            return ['codigo' => 404, 'mensagem' => 'Usuário ' . $maillogin . ' não encontrado!'];
        }
        //verificando se usuário tem permissão
        if ($empresa) {
            $qb = $this->getEntityManager()->createQueryBuilder()
                ->select('*')
                ->from(\Core\Entity\Transportes\UsuarioEmpresa::class, 'Usuario')
                ->where('Usuario.id_usuario = ?1')
                ->andWhere('Usuario.id_empresa = ?2')
                ->setParameters(array(1 => $result->usuario))
                ->setParameters(array(2 => $empresa));
            $result = $qb->getQuery()->getResult();
            if (!$result) {

                return ['codigo' => 404, 'mensagem' => 'Usuário ' . $maillogin . ' sem relação com essa empresa!'];
            }
        }
    }

    /**
     * @param null $fields
     * @param null $data
     * @throws \Exception
     */
    protected function verifyParams($fields = null, $data = null)
    {
        /*        $keys = array_keys($data);
                foreach ($fields as $f) {
                    if ($f['nullable'] == false && !in_array($f['name'], $keys)) {
                        throw new \Exception("O parâmetro {$f['name']} é obrigatório.");
                    }
                    if (!self::verifyType($f['type'], $data['keys'])) {
                        throw new \Exception("O parâmetro {$f['name']} deve ser do tipo {$f['type']}");
                    }
                }*/
    }

    protected function sort_by_field(&$arr, $field, $asc = true)
    {
        usort($arr, function ($a, $b) use ($field, $asc) {
            if ($a[$field] == $b[$field]) return 0;

            if ($asc) return ($a[$field] > $b[$field]) ? -1 : 1;
            else return ($a[$field] < $b[$field]) ? -1 : 1;
        });
    }

    protected function verifyType($type, $data)
    {
        //implementar função para validar o tipo do parâmetro

        return true;
    }

    protected function getUserTransporteByEmail($email)
    {

        return $this->getEntityManager()->getRepository(\Core\Entity\Transportes\Usuario::class)
            ->findOneBy(['email' => $email]);
    }

    protected function montaDataSP($data)
    {
        $data = explode('/', $data);

        return new \DateTime($data[0] . '-' . $data[1] . '-' . $data[2], new \DateTimeZone('America/Sao_Paulo'));
    }

    protected function montaData($data, $hora)
    {
        $data = explode('/', $data);

        return new \DateTime($data[2] . '-' . $data[1] . '-' . $data[0] . ' ' . $hora);
    }

    protected function montaDataComp($data)
    {
        $data = explode('/', $data);

        return new \DateTime($data[0] . '-' . $data[1] . '-' . $data[2]);
    }

    protected function montaDataUTC($data)
    {
        $data = explode('/', $data);

        return new \DateTime($data[0] . '-' . $data[1] . '-' . $data[2], new \DateTimeZone('UTC'));
    }

    function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    public static function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        return $angle * $earthRadius;
    }

    public function em(&$em)
    {
        if (!$em->isOpen()) {
            $em = $em->create(
                $em->getConnection(), $em->getConfiguration());
        }
    }

    public function emReset(&$em)
    {
        $em->getConnection()->close();
        $em->getConnection()->connect();
    }

    public function wakeUpEm(&$em)
    {
        try {
            if (!$em->getConnection()->ping()) {
                $em->getConnection()->close();
                $em->getConnection()->connect();
            }
        } catch (\Exception $e) {
            $em->getConnection()->close();
            $em->getConnection()->connect();
            if (!$em->getConnection()->ping()) {
                throw $e;
            }
        }
    }

    public function gerarQrCode($data, $params = [])
    {
        if (!isset($params['version'])) {
            $params['version'] = 5;
        }
        if (!isset($params['outputType'])) {
            $params['outputType'] = QRCode::OUTPUT_IMAGE_PNG;
        }
        if (!isset($params['eccLevel'])) {
            $params['eccLevel'] = QRCode::ECC_L;
        }
        $options = new QROptions($params);
        $data = is_array($data) ? json_encode($data) : $data;

        return (new QRCode($options))->render($data);
    }

    public function paginacao($data, $select, $coluna = null)
    {
        /*        $columns = $data['columns'];
                if ($data['order']) {
                    foreach ($data['order'] as $order) {
                        $select->addOrderBy($coluna[$columns[$order['column']]['data']], $order['dir']);
                    }
                }*/
        $retorno = $this->criarPaginacao($select, $data);

        return $this->paginatorData($retorno);
    }

    private function criarPaginacao($select, $request)
    {
        $paginator = $this->criarPaginator($select, $request);
        $page = 0;
        if ($request['start'] && $request['length']) {
            $page = $request['start'] / $request['length'];
        }
        $page++;
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }

    public function criarPaginator($select, $request)
    {
        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($request['length']);
        $paginator->setCacheEnabled(true);

        return $paginator;
    }

    public function paginatorData($paginator)
    {

        return array(
            'recordsTotal' => $paginator->getTotalItemCount(),
            'recordsFiltered' => $paginator->getTotalItemCount(),
            'data' => $this->dadosPagina($paginator)
        );
    }

    public function dadosPagina($paginator)
    {
        $data_page = array();
        foreach ($paginator->getCurrentItems() as $item) {
            $data_page[] = $item->getArrayCopy();
        }

        return $data_page;
    }

//    Parte genérica, para atender a todos os casos de CRUD básico, caso precise de algo mais especfíco deve ser criado

    public function create($data, $criar, $nome)
    {
        $has = get_object_vars($criar);
        foreach ($has as $name => $oldValue) {
            $criar->$name = isset($data[$name]) ? $data[$name] : NULL;
        }
        try {
            $this->em->persist($criar);
            $this->em->flush();

            return $criar;
        } catch (\Exception $e) {

            return ['codigo' => 500, 'mensagem' => 'Erro ao criar ' . $nome . '!'];
        }
    }

    public function removeList($data, $entity, $nome, $client_id)
    {
        foreach ($data as $key => $value) {
            $retorno = $this->remove($value, $entity, $nome, $client_id);
            if ($retorno['codigo'] != 200) {
                $naoremovido = ' ' . $value;
            } else {
                $removido = ' ' . $value;
            }
        }

        return ['codigo' => 200, 'mensagem' => $nome . ' removidos: ' . $removido . ' Programas não removidos: ' . $naoremovido];
    }

    public function remove($data, $entity, $nome, $client_id)
    {
        $remover = $this->em->find($entity, $data);
        if (!$remover) {

            return ['codigo' => 404, 'mensagem' => $nome . ' não encontrado'];
        }
        $this->em->remove($remover);
        try {
            $this->em->flush();

            return ['codigo' => 200, 'mensagem' => $nome . ' removido com sucesso!'];
        } catch (\Exception $e) {

            return ['codigo' => 304, 'mensagem' => $nome . ' não removido! Verifique dependências.'];
        }
    }

    public function get($data, $entity, $nome, $client_id = null)
    {
        $get = $this->em->find($entity, $data);
        if (!$get) {

            return ['codigo' => 404, 'mensagem' => $nome . ' não encontrado'];
        }

        return $get;
    }

    public function removeListByParams($data)
    {
        $rows = $this->em->getRepository($this->entitypath)->findBy($data);

        if ($rows) {
            foreach ($rows as $row) {
                $this->em->remove($row);
            }
            try {
                $this->em->flush();
            } catch (\Exception $e) {
                throw new \Exception("Erro ao remover {$this->nome}", 304);
            }
        } else {
            throw new \Exception("{$this->nome} não encontrado!", 404);
        }
    }

    function getConfig()
    {
        return include 'config/application.config.php';
    }

    function getConfigPushNotification()
    {
        $application_config = $this->getConfig();
        return $application_config['push_notification'];
    }

    public function sendNotificationByPlayersId($content, $players_id = [])
    {

        $headings = array(
            'en' => $content['titulo']
        );

        $content = array(
            'en' => $content['conteudo']
        );

        $CONFIG = $this->getConfigPushNotification();

        $fields = $CONFIG['fields'];
        $rest_api_key = $CONFIG['rest_api_key'];

        if ($players_id === 'all')
            $fields['included_segments'] = array('All');
        else if (!empty($players_id))
            $fields['include_player_ids'] = $players_id;
        $fields['contents'] = $content;
        $fields['headings'] = $headings;

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            "Authorization: Basic {$rest_api_key}"));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_close($ch);

        return curl_exec($ch);
    }

    public function findGenericAll($params = [], $entitypath, $entityname, $nome, $orderby = null, $orderascdesc = null, $client_id = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select($entityname)
            ->from($entitypath, $entityname);
        if ($orderby != null)
            $qb->orderBy($orderby, $orderascdesc);
        $r = $qb->getQuery()->getResult();
        if ($r) {

            return $r;
        }

        return ['codigo' => 404, 'mensagem' => 'Nenhum ' . $nome . ' foi encontrado!'];
    }

    public function updateList($data, $entity, $nome, $client_id)
    {
        foreach ($data as $key) {
            $retorno = $this->update($key, $data['id'], $entity, $nome, $client_id);
            if ($retorno['codigo'] != 200) {
                $naoremovido = ' ' . $key;
            } else {
                $removido = ' ' . $key;
            }
        }

        return ['codigo' => 200, 'mensagem' => $nome . ' removidos: ' . $removido . ' ' . $nome . ' não removidos: ' . $naoremovido];
    }

    public function update($data, $id, $entity, $nome, $client_id)
    {
        if (is_object($data))
            $data = json_decode(json_encode($data), True);
        $update = $this->em->find($entity, $data);
        if (!$update) {

            return ['codigo' => 404, 'mensagem' => $nome . ' não encontrado'];
        }
        $has = get_object_vars($update);
        foreach ($has as $name => $oldValue) {
            if ($name != 'criado')
                $update->$name = isset($data[$name]) ? $data[$name] : NULL;
        }
        $this->em->persist($update);
        try {
            $this->em->flush();

            return ['codigo' => 200, 'mensagem' => $update];
        } catch (\Exception $e) {

            return ['codigo' => 304, 'mensagem' => $nome . ' não modificado! ' . $e->getMessage()];
        }
    }


    /**
     * @param $event
     * @param int $size
     * @param string $unit m minute, h hour, d day
     * @throws \Exception
     */
    public function refreshToken($params)
    {
        $token = $params['token'];
        $size = $params['size'] ?? 500;
        $unit = $params['unit'] ?? 'm';
        $units = [
            'm' => 'minutes',
            'h' => 'hours',
            'd' => 'days'
        ];
        if (!isset($units[$unit])) {
            throw new \Exception('Unidade de tempo inválida');
        }
        $token = $this->em->getRepository(OauthAccessTokens::class)->findOneBy([
            'access_token' => $token
        ]);
        if (!$token) {

            return false;
        }
        $datahora = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $horaNova = strtotime("{$datahora} + {$size} {$units[$unit]}");
        $horaFormatada = new \DateTime(date("Y-m-d H:i:s", $horaNova));
        if($token->getExpires() > $horaFormatada){

            return false;
        }
        $token->setExpires($horaFormatada);
        $this->em->persist($token);
        try {
            $this->em->flush();
        } catch (\Exception $e) {
        }
    }

    public function montaDataHoraUTC($data, $hora)
    {
        return new \DateTime($data . ' ' . $hora, new \DateTimeZone('UTC'));
    }

    /**
     * @param $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Exception
     */
    protected function getDependencies(&$data, $entitypath)
    {
        $fields = $this->em->getClassMetadata($entitypath)->associationMappings;
        foreach ($fields as $field => $f) {
            if (isset($data[$field])) {
                $data[$field] = $this->em->find($f['targetEntity'], $data[$field]);
                if (!$data[$field]) {

                    throw new \Exception('Registro não encontrado');
                }
            }
        }
    }

    /**
     * @param $date
     * @param string $format
     * @return bool
     * @throws \Exception
     */
    protected function checkDate($date, $format = 'd/m/y')
    {
        if (!$date) {

            return false;
        }

        if(strlen($format) != 5){

            throw new \Exception('Formato inválido.');
        }
        $delimiter = '/';
        if (strstr($format, '-')) {
            $delimiter = '-';
        }

        $date = $this->organizeDate(explode($delimiter, $date), explode($delimiter, $format));
        if(!$date){

            return false;
        }

        return checkdate($date['m'], $date['d'], $date['y']);
    }

    private function organizeDate($date, $format)
    {
        $array = [];
        $count = 0;
        foreach ($format as $item) {
            if(!isset($date[$count])){

                return false;
            }
            $array[$item] = $date[$count];
            $count++;
        }

        return $array;
    }
}