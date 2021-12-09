<?php

/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;



/* Home */

function funcionHomePage()
{
    global $routes;

    $rutaListadoUsers = $routes->get('ruta_user_list')->getPath();
    $rutaUser = $routes->get('ruta_user_page')->getPath();
    $rutaListadoResults = $routes->get('ruta_result_list')->getPath();
    $rutaResults = $routes->get('ruta_result_page')->getPath();

    echo <<< ____MARCA_FIN
    <ul>
        <li><a href="$rutaListadoUsers">Listado Usuarios</a></li>
        <li><a href="$rutaUser">Usuario</a></li>
        <br/>
        <li><a href="$rutaListadoResults">Listado Results</a></li>
        <li><a href="$rutaResults">Result</a></li>
    </ul>
____MARCA_FIN;
}



/* Usuario */

function funcionListadoUsuarios(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($users);
}

function funcionUsuarioPage()
{
    echo <<< ____MARCA_FIN
    <style>
        h5 {
            margin: 3px 0;
            color: #444;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <h1>Usuario</h1>
    <input type="text" id="username" placeholder="username">
    <button class="button-search">Buscar</button>
    <button class="button-delete">Delete</button>
    <h1>Usuario Formulario</h1>
    <h5>ID (solo por modificar):</h5>
    <input type="text" id="id" placeholder="id">
    <h5>Username:</h5>
    <input type="text" id="username2" placeholder="username">
    <h5>E-Mail:</h5>
    <input type="text" id="email" placeholder="email">
    <h5>Password:</h5>
    <input type="password" id="password" placeholder="password">
    <div>
        <button class="button-create">Crear</button>
        <button class="button-modify">Modificar</button>
    </div>
    <div class="message"></div>
    <script>
        $(() => {
            $('.button-search').on('click', () => {
                const username = $('#username').val();
                $.ajax({
                    url: '/user/' + username,
                    method: 'GET',
                    success: (res) => {
                        $('.message').html(res);
                    },
                    error: () => {
                        $('.message').html('Error');
                    }
                });
            })
            $('.button-delete').on('click', () => {
                const username = $('#username').val();
                $.ajax({
                    url: '/user/' + username,
                    method: 'DELETE',
                    success: (res) => {
                        $('.message').html(res);
                    },
                    error: () => {
                        $('.message').html('Error');
                    }
                });
            })
            $('.button-create').on('click', () => {
                const username = $('#username2').val();
                const email = $('#email').val();
                const password = $('#password').val();
                $.ajax({
                    url: '/user',
                    method: 'POST',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: (res) => {
                        $('.message').html(res);
                    },
                    error: () => {
                        $('.message').html('Error');
                    }
                });
            })
            $('.button-modify').on('click', () => {
                const id = $('#id').val();
                const username = $('#username2').val();
                const email = $('#email').val();
                const password = $('#password').val();
                $.ajax({
                    url: '/user/' + id,
                    method: 'PUT',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: (res) => {
                        $('.message').html(res);
                    },
                    error: () => {
                        $('.message').html('Error');
                    }
                });
            })
        });
    </script>
____MARCA_FIN;
}

function funcionUsuario($parameters, $context)
{
    $username = $parameters['name'];
    if (empty($username)) {
        echo 'User name is empty';
        exit(0);
    }

    if ($context->getMethod() == 'GET') {
        $entityManager = DoctrineConnector::getEntityManager();

        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findBy(array('username' => $username));
        var_dump($users);
    } else if ($context->getMethod() == 'DELETE') {
        $entityManager = DoctrineConnector::getEntityManager();

        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findBy(array('username' => $username));
        if (empty($users)) {
            echo 'Not Found User: ' . $username;
            exit(0);
        }

        $resultsRepository = $entityManager->getRepository(Result::class);
        foreach ($users as $user) {
            $userId = $user->getId();
            $results = $resultsRepository->findBy(array('user_id' => $userId));
            foreach ($results as $result) {
                $entityManager->remove($result);
            }
            $entityManager->remove($user);
            $entityManager->flush();
            echo 'Deleted User with ID ' .$userId;
        }
    }
}

function functionCreateUsuario(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $user = new User();
    $user->setUsername($_POST['username']);
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);
    $user->setEnabled(true);
    $user->setIsAdmin(true);
    try {
        $entityManager->persist($user);
        $entityManager->flush();
        echo 'Created Admin User with ID #' . $user->getId() . PHP_EOL;
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
    }
}

function functionModifyUsuario($parameters, $context): void
{
    $userId = $parameters['id'];
    if (empty($userId)) {
        echo 'User id is empty';
        exit(0);
    }
    $entityManager = DoctrineConnector::getEntityManager();

    parse_str(file_get_contents("php://input"),$_POST);

    $usersRepository = $entityManager->getRepository(User::class);
    $user = $usersRepository->find($userId);
    $user->setUsername($_POST['username']);
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);
    try {
        $entityManager->persist($user);
        $entityManager->flush();
        echo 'Modified User with ID ' . $user->getId();
    } catch (Throwable $exception) {
        echo $exception->getMessage();
    }
}



/* Results */

function funcionListadoResults(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findAll();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($results);
}

function funcionResultPage()
{
    echo <<< ____MARCA_FIN
    <style>
        h5 {
            margin: 3px 0;
            color: #444;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <h1>Result</h1>
    <input type="text" id="id" placeholder="id">
    <button class="button-search">Buscar</button>
    <button class="button-delete">Delete</button>
    <h1>Result Formulario</h1>
    
    <h5>ID (Solo por modificar):</h5>
    <input type="text" id="id" placeholder="id">
    <h5>Usuario:</h5>
    <select id="user"></select>
    <h5>Result:</h5>
    <input type="text" id="result" placeholder="result">
    <div style="margin-top: 2px;">
        <button class="button-create">Crear</button>
        <button class="button-modify">Modificar</button>
    </div>
    <div class="message"></div>
    <script>
        $(() => {
            $.ajax({
                url: '/users',
                method: 'GET',
                success: (data) => {
                    for(const i of data) {
                        $("#user").append("<option value=\"" + i.id + "\">" + i.username + "</option>")
                    }
                }
            });
            $('.button-search').on('click', () => {
                const id = $('#id').val();
                $.ajax({
                    url: '/result/' + id,
                    method: 'GET',
                    success: (res) => {
                        $('.message').html(res);
                    },
                    error: () => {
                        $('.message').html('Error');
                    }
                });
            })
            $('.button-delete').on('click', () => {
                const id = $('#id').val();
                $.ajax({
                    url: '/result/' + id,
                    method: 'DELETE',
                    success: (res) => {
                        $('.message').html(res);
                    },
                    error: () => {
                        $('.message').html('Error');
                    }
                });
            })
            $('.button-create').on('click', () => {
                const user_id = $("#user").val();
                const result = $('#result').val();
                $.ajax({
                    url: '/result',
                    method: 'POST',
                    data: {
                        user_id: user_id,
                        result: result
                    },
                    success: (res) => {
                        $('.message').html(res);
                    },
                    error: () => {
                        $('.message').html('Error');
                    }
                });
            })
            $('.button-modify').on('click', () => {
                const id = $('#id').val();
                const user_id = $("#user").val();
                const result = $('#result').val();
                $.ajax({
                    url: '/result/' + id,
                    method: 'PUT',
                    data: {
                        user_id: user_id,
                        result: result
                    },
                    success: (res) => {
                        $('.message').html(res);
                    },
                    error: () => {
                        $('.message').html('Error');
                    }
                });
            })
        });
    </script>
____MARCA_FIN;
}

function funcionResult($parameters, $context)
{
    $resultId = $parameters['id'];
    if (empty($resultId)) {
        echo 'Result ID is empty';
        exit(0);
    }
    if ($context->getMethod() == 'GET') {
        $entityManager = DoctrineConnector::getEntityManager();
        $resultsRepository = $entityManager->getRepository(Result::class);
        $result = $resultsRepository->find($resultId);
        var_dump($result);
    } else if ($context->getMethod() == 'DELETE') {
        $entityManager = DoctrineConnector::getEntityManager();
        $resultsRepository = $entityManager->getRepository(Result::class);
        $result = $resultsRepository->find($resultId);
        if (empty($result)) {
            echo 'Not Found Result: ' . $resultId;
            exit(0);
        }
        $entityManager->remove($result);
        $entityManager->flush();
        echo 'Deleted Result with ID ' .$resultId;
    }
}

function functionCreateResult(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $newResult = $_POST['result'];
    $newTimestamp = new DateTime('now');

    $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $_POST['user_id']]);

    $result = new Result($newResult, $user, $newTimestamp);
    try {
        $entityManager->persist($result);
        $entityManager->flush();
        echo 'Created Result with ID ' . $result->getId()
            . ' USER ' . $user->getUsername() . PHP_EOL;
    } catch (Throwable $exception) {
        echo $exception->getMessage();
    }
}

function functionModifyResult($parameters, $context): void
{
    $resultId = $parameters['id'];
    if (empty($resultId)) {
        echo 'Result id is empty';
        exit(0);
    }
    $entityManager = DoctrineConnector::getEntityManager();

    parse_str(file_get_contents("php://input"), $_POST);

    $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $_POST['user_id']]);

    $resultsRepository = $entityManager->getRepository(Result::class);
    $result = $resultsRepository->find($resultId);
    $result->setResult($_POST['result']);
    $result->setUser($user);
    try {
        $entityManager->persist($result);
        $entityManager->flush();
        echo 'Modified Result with ID ' . $result->getId();
    } catch (Throwable $exception) {
        echo $exception->getMessage();
    }
}

