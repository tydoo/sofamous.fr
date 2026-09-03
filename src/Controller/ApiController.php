<?php

namespace App\Controller;

use App\Entity\EmailTellDevelopper;
use App\Repository\EmailTellDevelopperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api.')]
final class ApiController extends AbstractController {

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MailerInterface $mailer
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response {
        return $this->render('swagger-ui.html.twig');
    }

    private function returnErrorResponse(string $message, ?int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse {
        return new JsonResponse([
            'code' => $statusCode,
            'error' => $message
        ], $statusCode);
    }

    #[Route('/v1/email', name: 'email.post', methods: ['POST'])]
    public function postEmail(
        Request $request,
        EmailTellDevelopperRepository $emailTellDevelopperRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $emailFromRequest = $data['email'] ?? null;

        if (!filter_var($emailFromRequest, FILTER_VALIDATE_EMAIL)) {
            return $this->returnErrorResponse('Invalid email address', Response::HTTP_BAD_REQUEST);
        }

        if ($email = $emailTellDevelopperRepository->findOneBy(['email' => $emailFromRequest])) {
            return new JsonResponse(
                $email->toArray()
            );
        }

        $email = new EmailTellDevelopper($emailFromRequest);

        $emailToSend = new Email();
        $emailToSend
            ->to('tboyer@sofamous.fr')
            ->subject('New email submission')
            ->html('<p>A new email has been submitted.</p>');

        try {
            $this->em->persist($email);
            $this->em->flush();

            $this->mailer->send($emailToSend);

            return new JsonResponse(
                $email->toArray(),
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            return $this->returnErrorResponse('An error occurred while saving the email', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
