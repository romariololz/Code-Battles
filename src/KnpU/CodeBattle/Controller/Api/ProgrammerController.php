<?php

namespace KnpU\CodeBattle\Controller\Api;

use KnpU\CodeBattle\Controller\BaseController;
use KnpU\CodeBattle\Model\Programmer;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProgrammerController extends BaseController
{
    protected function addRoutes(ControllerCollection $controllers)
    {
         $controllers->post('/api/programmers', array($this, 'newAction'));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $programmer = new Programmer();
        $programmer->nickname = $data['nickname'];
        $programmer->avatarNumber = $data['avatarNumber'];
        $programmer->tagLine = $data['tagLine'];
        $programmer->userId = $this->findUserByUsername('weaverryan')->id;

        $this->save($programmer);

        $response = new Response('It worked! Trust me, I\'m an API!', 201);
        $response->headers->set('Location', '/programmers/not/fake...');

        return $response;
    }

}
