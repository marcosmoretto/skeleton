<?php

namespace User\V1\Rest\User;

use Core\Resource\AbstractResource;
use Core\Service\Mail\Mail;
use Core\Service\Transportes\Tecnologia as Service;
use Core\Service\Transportes\User;
use Core\Service\Transportes\UserPerfil;
use ZF\ApiProblem\ApiProblem;

class UserResource extends AbstractResource
{

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    protected $userMapper;
    protected $em;
    protected $userService;
    protected $db;
    protected $mail;

    public function __construct($services)
    {
        $this->service = new Service($services->get('Doctrine\ORM\EntityManager'));
        $db = $services->get('oauth2');
        $userProfileMapper = $services->get(\User\Mapper\UserProfile::class);
        $this->setUserMapper($userProfileMapper);
        $this->db = $db;
        parent::__construct($services, $this->service);
    }

    public function create($data)
    {
        $data = $this->getInputFilter()->getValues();

        $mail = new Mail($this->em);
        $usuario = new User($this->em);
        $userPerfil = new UserPerfil($this->em);
        try {
            $data["usuario_id"] = $usuario->createUser($data, $this->db, $mail);
            if (isset($data['tamanho']) and isset($data['modulos'])) {
                $userPerfil->criarPerfil($data);
            }
            return new ApiProblem(201, 'Usuario criado com sucesso');
        } catch (\Exception $e) {
            return new ApiProblem(409, $e);
        }
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $event = $this->getEvent();
        $values = $event->getIdentity()->getAuthenticationIdentity();
        $usuario = new User($this->em);
        try {
            $usuario->logout($values);
            exit;
        } catch (\Exception $e) {
            return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
        }
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {

        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $id = ltrim($id, ':');
        if (strpos($id, '@')) {//recendo email
            $service_user = new User($this->em);
            $valor = $service_user->findByEmail($id);
            if (!$valor) {

                return new ApiProblem(405, 'Nenhum valor encontrado');
            }
            $token = $this->getEvent()->getIdentity()->getAuthenticationIdentity()['access_token'];
            $valor->qrcode_token = $service_user->gerarQrCode($token);

            return $valor;
        } else {
            echo 'recebido hash';
        }
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        if (isset($params['email'])) {
            $id = ltrim($params['email'], ':');
            if (strpos($id, '@')) {//recendo email
                $service_user = new User($this->em);
                $valor = $service_user->findByEmail($id);
                if (!$valor) {

                    return new ApiProblem(405, 'Nenhum valor encontrado');
                }
                $token = $this->getEvent()->getIdentity()->getAuthenticationIdentity()['access_token'];
                $valor->qrcode_token = $service_user->gerarQrCode($token);

                return $valor;
            } else {
                return new ApiProblem(405, 'Verifique o parâmetro passado');
            }
        }
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        $id = ltrim($id, ':');
        $service_user = new User($this->em);
        $data = $this->getInputFilter()->getValues();
        $user = $service_user->findByHash($id);//busca dados do usuário
        if (!$user) {

            return new ApiProblem(405, 'Sua conta já está ativada!');
        }
        $mail = new Mail($this->em);
        if ($user->status->id == 2 && $user->dataAtivacao == null) {
            try {
                $service_user->emailNovaConta($user, $id, $mail);

                return true;
            } catch (\Exception $e) {

                return new ApiProblem(404, $e->getMessage());
            }
        }

        return new ApiProblem(405, 'Método não implementado');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        $id = ltrim($id, ':');
        if (strpos($id, '@')) {
            $service_user = new User($this->em);
            $user = $service_user->findByEmail($id);//busca dados do usuário

            $usuario = $this->getEvent()->getIdentity()->getAuthenticationIdentity();//seleciona o usuario atual logado e checa se ele esta editando o proprio usuario
            if ($data->login_email == $usuario['client_id'] && $data->login_token == $usuario['access_token']) {
                if ($data->password) {
                    $senhaCorreta = $service_user->verificaSenhaAntiga($user, $data);
                    if (!$senhaCorreta) {

                        return new ApiProblem(405, 'Senha antiga incorreta!.');
                    }
                }
                if ($data->confirmPassword || $data->newPassword)
                    if ($data->confirmPassword != $data->newPassword) {

                        return new ApiProblem(405, 'As Senha estão diferentes');
                    }
                try {
                    $service_user->salvarEdicaoUsuario($user, $data);
                } catch (\Exception $e) {

                    return new ApiProblem(404, $e->getMessage());
                }

                return new ApiProblem(201, 'Perfil editado com sucesso!');
            } else {

                return new ApiProblem(405, 'Verifique suas Credenciais.');
            }
        } else if (strpos($id, '@')) {
            $service_user = new User($this->em);
            $user = $service_user->findByEmail($id);//busca dados do usuário
            if ($user) {
                $usuario = $this->getEvent()->getIdentity()->getAuthenticationIdentity();
                if ($usuario['client_id'] == $id) {
                    $dados['password'] = $data->newPassword;
                    $dados['senhaantiga'] = $data->password;
                    $retorno = $service_user->editarSenha($user, $dados);
                    if (isset($retorno['mensagem'])) {

                        return new ApiProblem($retorno['codigo'], $retorno['mensagem']);
                    } else {

                        return new ApiProblem(200, 'Senha alterada com sucesso');
                    }
                } else {
                    $mail = new Mail($this->em);
                    if (!$service_user->recoverPass($mail, $user)){

                        return new ApiProblem(404, 'Nao foi possivel enviar o email de recuperacao de senha');
                    }

                    return new ApiProblem(200, 'Email enviado com sucesso');
                }
            }
        } else {
            $service_user = new User($this->em);
            $data = $this->getInputFilter()->getValues();
            $user = $service_user->findByHash($id);//busca dados do usuário
            if (!$user) {

                return new ApiProblem(405, 'Sua conta já está ativada!');
            }
            if ($user->status->id == 2 && $user->dataAtivacao == null) {
                try {

                    return $service_user->ativarConta($user, $this->db);
                } catch (\Exception $e) {

                    return new ApiProblem(404, $e->getMessage());
                }
            } else {
                try {

                    return $service_user->editarSenha($user, $data);
                } catch (\Exception $e) {

                    return new ApiProblem(404, $e->getMessage());
                }
            }
        }

        return new ApiProblem(404, 'Seu token de recuperação de senha ou ativação de conta expirou, solicite um novo.');
    }

    /**
     * @return the $userProfileMapper
     */
    public function getUserMapper()
    {
        return $this->userMapper;
    }

    /**
     * @param UserProfileMapper $userProfileMapper
     */
    public function setUserMapper($userMapper)
    {
        $this->userMapper = $userMapper;
    }

    public function getUserService()
    {
        return $this->userService;
    }

    /**
     * @param UserProfileMapper $userProfileMapper
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param $data Array de Dados da do Usuário
     */
    protected function criarPerfilUsuario($data)
    {
        $user_perfil = new UserPerfil($this->em);
        $user_perfil->criarPerfil($data);
    }
}
