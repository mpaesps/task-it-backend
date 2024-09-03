<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task', name: 'task')]
class TaskController extends AbstractController
{
    #[Route(name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['title'])) {
            return new JsonResponse(
                (object)['erro' => 'O campo title é obrigatório!'],
                Response::HTTP_BAD_REQUEST
            );
        }
        $task = new Task();
        $task->setTitle($data['title']);
        $task->setContent($data['content'] ?? '');

        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse($task, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['title']) && isset($data['content']) && isset($data['isCompleted'])) {

            $task = $entityManager->find(Task::class, $id);

            if ($task) {
                $task->setTitle($data['title']);
                $task->setContent($data['content']);
                $task->setIsCompleted($data['isCompleted']);

                $entityManager->persist($task);
                $entityManager->flush();

                return new JsonResponse($task, Response::HTTP_OK);
            }

            return new JsonResponse(
                ['erro' => 'Tarefa não encontrada!'],
                Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(
            ['erro' => 'O campo title, content e isCompleted são obrigatórios!'],
            Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteTask(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $task = $entityManager->find(Task::class, $id);

        if ($task) {
            $entityManager->remove($task);
            $entityManager->flush();
            return new JsonResponse(
                (object)['mensagem' => 'Tarefa excluída com sucesso!'], Response::HTTP_OK);
        }
        return new JsonResponse(
            (object)['erro' => 'Tarefa não encontrada!'],
        );
    }

    #[Route(name: 'get', methods: ['GET'])]
    public function getTasks(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $listTasks = $entityManager->getRepository(Task::class)->findAll();
        return $this->json($listTasks);
    }
}
