
Esqueleto para desenvolvimento de API usando ZF3, Apigility com suporte ao OAuth2  
===========================================  
  
Requerimentos  
------------  
  
Tudo que é necessário para rodar o projeto tá no [composer.json](composer.json).  
  
Clone  
---  
  
Depois de clonar, execute:   
  
```  
$ composer install  
```  
Vai instalar todas as dependências necessárias para o projeto.  
  
Para rodar, inicie um server php ou então configure um VirtualHost do apache na raíz do projeto, por exemplo:
Na pasta <camimho projeto>/public
```
cd /var/www/html/skeleton/public
php -S 0.0.0.0:8080
```

Aí é só acessar no navegador algo como: [http://127.0.0.1](http://127.0.0.1).  
  
O Apigility possui uma interface bem intuitiva, você provavelmente não terá problemas para  
utilizar.  

- ### Vai usar Gearman?  
Então verifique se alguém já instalou no servidor onde o projeto ficará hospedado.  
  
```bash  
$ gearmand --help
``` 
Se retornar a ajuda, tudo certo, caso contrário vai ter que instalar. Para instalar tem esses dois links:  

- [primeiro](https://bobbyiliev.com/blog/step-step-guide-install-gearman-php-7-x-cpanel-ea-4-server/)
- [segundo](https://gist.github.com/hieubuiduc/105989131e1192e0d680)

Para o Gearman começar a rodar, basta executar o seguinte comando  
```bash  
$ gearmand -d
``` 
O comando acima irá iniciar um daemon do Gearman.  
  
Comandos úteis  
  
```bash  
$ gearadmin --status
$ gearadmin --shutdown
```


---
Utilizando O oauth
---
Rodar o sql no banco
/vendor/zfcampus/zf-oauth2/data/db_oauth2_postgresql.sql

Logar com usuário, enviar POST para a URL http://127.0.0.1/oauth com o JSON no formato abaixo
{
"client_id":"marcos", "client_secret":"1234","grant_type":"client_credentials", "access":"Site"
}


Verificar se o usuário está logado
http://127.0.0.1/oauth/resource
Adicionar o cabeçalho
Content-Type: application/json
Authorization: Bearer asgsajglkajlajlasjflkasjçlgjasgçlajslk