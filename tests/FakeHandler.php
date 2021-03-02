<?php

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Laminas\Diactoros\Response as Response;

class FakeHandler implements RequestHandlerInterface {

  public function handle(ServerRequestInterface $request): ResponseInterface {
    return new Response();
  }

}

?>
