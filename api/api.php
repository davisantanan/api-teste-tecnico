<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
    }
   
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"));
        
        function generateUniqueId(){
            return md5(uniqid(mt_rand(), true));
        }
        
        if (
            !empty($data->email) 
            && !empty($data->nome)
            && !empty($data->telefone)
            && !empty($data->cpf)
            && !empty($data->cep)
            && !empty($data->endereco)
            && !empty($data->numero)
            && !empty($data->cidade)
            && !empty($data->bairro)
            && !empty($data->estado)
            && !empty($data->envio) 
            ) {
            $id = generateUniqueId();
            $email = $data->email;
            $nome = $data->nome;
            $telefone = $data->telefone;
            $cpf = $data->cpf;
            $cep = $data->cep;
            $endereco = $data->endereco;
            $numero = $data->numero;
            $complemento = isset($data->complemento) ? $data->complemento : '';
            $bairro = $data->bairro;
            $cidade = $data->cidade;
            $estado = $data->estado;
            $envio = $data->envio;
        
            $fileData = 
            "ID: $id\n
            Email: $email\n
            Nome: $nome\n
            Telefone: $telefone\n
            CPF: $cpf\n
            CEP: $cep\n
            Endereco: $endereco\n
            Numero: $numero\n
            Complemento: $complemento\n
            Bairro: $bairro\n
            Cidade: $cidade\n
            Estado: $estado\n
            Envio: $envio\n\n   
            ";
            
            $filePath = "data.txt"; 
            
            if ($fileHandle = fopen($filePath, 'a')) {
                
                if (fwrite($fileHandle, $fileData) !== false) {
                    fclose($fileHandle); 
                    http_response_code(201); 
                    echo json_encode(array("message" => "Dados inseridos com sucesso."));
                } else {
                    http_response_code(500); 
                    echo json_encode(array("message" => "Erro ao escrever os dados no arquivo."));
                }
            } else {
                http_response_code(500); 
                echo json_encode(array("message" => "Erro ao abrir o arquivo para escrita."));
            }
        } else {
            http_response_code(400); 
            echo json_encode(array("message" => "Dados incompletos."));
        }
    } elseif($method === 'GET') {
        $filePath = "data.txt";

        if (file_exists($filePath)) {
            $fileContents = file_get_contents($filePath);
            $data = explode("\n\n", $fileContents);
            $formattedData = [];
    
            foreach ($data as $entry) {
                $entry = str_replace("\r", '', $entry); 
                $entryData = explode("\n", $entry);
                $formattedEntry = [];
        
                foreach ($entryData as $line) {
                    $line = trim($line); 
                    if (!empty($line)) {
                        list($key, $value) = explode(":", $line, 2); 
                        $formattedEntry[$key] = $value;
                    }
                }
        
                if (!empty($formattedEntry)) {
                    $formattedData[] = $formattedEntry;
                }
            }
        
            echo json_encode($formattedData);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Nenhum dado encontrado."));
        }  
    }
    
    else {
        http_response_code(405); 
        echo json_encode(array("message" => "Método não permitido."));
    }
?>