<?php
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"));
        
        
        if (
            !empty($data->email) 
            && !empty($data->nome)
            && !empty($data->telefone)
            && !empty($data->cpf)
            && !empty($data->cep)
            && !empty($data->endereco)
            && !empty($data->numero)
            && !empty($data->complemento)
            && !empty($data->cidade)
            && !empty($data->bairro)
            && !empty($data->estado)
            && !empty($data->envio) 
            ) {
            $email = $data->email;
            $nome = $data->nome;
            $telefone = $data->telefone;
            $cpf = $data->cpf;
            $cep = $data->cep;
            $endereco = $data->endereco;
            $numero = $data->numero;
            $complemento = $data->complemento;
            $bairro = $data->bairro;
            $cidade = $data->cidade;
            $estado = $data->estado;
            $envio = $data->envio;
            

            $fileData = 
            "Email: $email\n
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
                // Escreva os dados no arquivo
                if (fwrite($fileHandle, $fileData) !== false) {
                    fclose($fileHandle); // Feche o arquivo
                    http_response_code(201); // Código de resposta 201 Created
                    echo json_encode(array("message" => "Dados inseridos com sucesso."));
                } else {
                    http_response_code(500); // Código de resposta 500 Internal Server Error
                    echo json_encode(array("message" => "Erro ao escrever os dados no arquivo."));
                }
            } else {
                http_response_code(500); // Código de resposta 500 Internal Server Error
                echo json_encode(array("message" => "Erro ao abrir o arquivo para escrita."));
            }
        } else {
            http_response_code(400); // Código de resposta 400 Bad Request
            echo json_encode(array("message" => "Dados incompletos."));
        }
    } else {
        http_response_code(405); // Código de resposta 405 Method Not Allowed
        echo json_encode(array("message" => "Método não permitido."));
    }
    

?>