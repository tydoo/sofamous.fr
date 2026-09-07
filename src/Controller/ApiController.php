<?php

namespace App\Controller;

use App\Entity\EmailTellDevelopper;
use App\Repository\EmailTellDevelopperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\MailService;

#[Route('/api', name: 'api.')]
final class ApiController extends AbstractController {

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MailService $mailService,
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
        EmailTellDevelopperRepository $emailTellDeveloperRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $emailFromRequest = $data['email'] ?? null;

        if (!filter_var($emailFromRequest, FILTER_VALIDATE_EMAIL)) {
            return $this->returnErrorResponse('Invalid email address', Response::HTTP_BAD_REQUEST);
        }

        if ($email = $emailTellDeveloperRepository->findOneBy(['email' => $emailFromRequest])) {
            return new JsonResponse(
                $email->toArray()
            );
        }

        $email = new EmailTellDevelopper($emailFromRequest);

        try {
            $this->em->persist($email);
            $this->em->flush();

            $html = "
            <p style=\"margin: 0 0 16px; font-size: 15px; line-height: 24px; color: #1d1d1d;\">A new email has been submitted from the website:</p>
            <p style=\"margin: 16px 0; padding: 16px 20px; background-color: #f4f5f7; border: 1px solid #e4e7ec; border-radius: 8px; font-size: 15px; font-weight: 600; color: #1d1d1d; word-break: break-all;\">" . htmlspecialchars($emailFromRequest, ENT_QUOTES, 'UTF-8') . "</p>
            ";

            $this->mailService->sendEmail(
                to:'tboyer@sofamous.fr',
                subject:'New email from the website',
                body: $this->renderView('emails/simple.html.twig', [
                    'subject' => 'New email from the website',
                    'content' => $html
                ])
            );

            return new JsonResponse(
                $email->toArray(),
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            throw $th;
            return $this->returnErrorResponse('An error occurred while saving the email', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
