<?php
    require_once '../../vendor/autoload.php';
    require_once 'db.php';
    
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    
    
    $KEY = 'clave';
    
    function crearToken($usuario){
        $pyload = [
            'usuario' => $usuario,
        ];
        $jwt = JWT::encode($pyload, $GLOBALS['KEY'], 'HS256');
        return $jwt;
    }
    
    function decifrarToken($jwt){
        $key = $GLOBALS['KEY'];
        $pyload = JWT::decode($jwt, new Key($key, 'HS256'));
        $usuario = $pyload->usuario;
        if (confirmarToken($usuario)){
            return $usuario;
        } else {
            return null;
        }
        
    }
    function decifrarTokenAdmin($jwt){
        $key = $GLOBALS['KEY'];
        $pyload = JWT::decode($jwt, new Key($key, 'HS256'));
        $usuario = $pyload->usuario;
        $confirm = confirmarTokenAdmin($usuario);
        if (confirmarTokenAdmin($usuario)){
            return $usuario;
        } else {
            return null;
        }   
    }
?>