<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config/config.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);


$app->get('/', function (Request $request, Response $response, $args) {
    $sql = "SELECT * FROM clientes";    
    $db = new DB();
    try{
        $cnx = $db->conectar();
        $result = $cnx->query($sql);
        $clientes = $result->fetchAll(PDO::FETCH_OBJ);

        $cnx = null;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    $response->getBody()->write(json_encode($clientes,JSON_UNESCAPED_UNICODE));
    return $response;
});

$app->get('/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $sql = "SELECT * FROM clientes WHERE id = $id";    
    $db = new DB();
    try{
        $cnx = $db->conectar();
        $result = $cnx->query($sql);
        $clientes = $result->fetchAll(PDO::FETCH_OBJ);

        $cnx = null;

    } catch(PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
    $response->getBody()->write(json_encode($clientes,JSON_UNESCAPED_UNICODE));
    return $response;


});

$app->post('/', function (Request $request, Response $response,$args){
    $data = date('d-m-Y');
    $nome = $request->getParsedBody()["nome"];
    $telefone = $request->getParsedBody()["telefone"];
    $email = $request->getParsedBody()["email"];
    $cep = $request->getParsedBody()["cep"];
   
    $sql = "INSERT INTO clientes (data,nome,telefone,email,cep) VALUES (:data,:nome,:telefone,:email,:cep)";

    $db = new DB();
    try{
        $cnx = $db->conectar();
        $pstm = $cnx->prepare($sql);
        $pstm->bindParam(":data",$data);
        $pstm->bindParam(":nome",$nome);
        $pstm->bindParam(":telefone",$telefone);
        $pstm->bindParam(":email",$email);
        $pstm->bindParam(":cep",$cep);

        $result = $pstm->execute();
        $cnx = null;

        $response->getBody()->write(json_encode($body,JSON_UNESCAPED_UNICODE));
        return $response;
        
    } catch(PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
        
});

$app->put('/{id}', function (Request $request, Response $response,$args){
    
    $id = $args['id'];
    $nome = $request->getParsedBody()["nome"];
    $telefone = $request->getParsedBody()["telefone"];
    $email = $request->getParsedBody()["email"];
    $cep = $request->getParsedBody()["cep"];
   
    $sql = "UPDATE clientes SET nome=:nome,telefone=:telefone,email=:email,cep=:cep WHERE id=:id";

    $db = new DB();
    try{
        $cnx = $db->conectar();
        $pstm = $cnx->prepare($sql);
        $pstm->bindParam(":nome",$nome);
        $pstm->bindParam(":telefone",$telefone);
        $pstm->bindParam(":email",$email);
        $pstm->bindParam(":cep",$cep);
        $pstm->bindParam(":id",$id);

        $result = $pstm->execute();
        $cnx = null;

        $response->getBody()->write(json_encode($body,JSON_UNESCAPED_UNICODE));
        return $response;
        
    } catch(PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
        
});

$app->delete('/{id}',function ( Request $req,Response $res,$args ){

    $id = $args['id'];
    $sql = "DELETE FROM clientes WHERE id=:id";
    $db = new DB();
    try{
        $cnx = $db->conectar();
        $pstm = $cnx->prepare($sql);
        $pstm->bindParam(":id",$id);
        $pstm->execute(); 
        $cnx = null;
        
    } catch(PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
    $response->getBody()->write("Sucesso!");
    return $response;

});

$app->run();