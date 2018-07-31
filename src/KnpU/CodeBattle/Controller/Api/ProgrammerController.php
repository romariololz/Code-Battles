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
         $controllers->get('/api/programmers/{nickname}', [$this, 'showAction'])
             ->bind('api_programmers_show');
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

        $response = new Response('It worked. Believe me - I\'m an API', 201);
        $programmerUrl = $this->generateUrl(
            'api_programmers_show',
            ['nickname' => $programmer->nickname]
        );
        $response->headers->set('Location', $programmerUrl);

        return $response;

    }

    public function showAction($nickname)
    {
        $programmer = $this->getProgrammerRepository()
            ->findOneByNickname($nickname);

        if (!$programmer) {
            $this->throw404('Oh no! This programmer has deserted! We\'ll send a search party!');
        }

        $data = [
            'nickname'      => $programmer->nickname,
            'avatarNumber'  => $programmer->avatarNumber,
            'powerLevel'    => $programmer->powerLevel,
            'tagLine'       => $programmer->tagLine,
        ];

        $response = new Response(json_encode($data), 200);

        return $response;
    }
}
