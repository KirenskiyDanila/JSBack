<?php

namespace App\Controller;

use App\Entity\EducationOption;
use App\Entity\Resume;
use App\Repository\EducationOptionRepository;
use App\Repository\ResumeRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    private Serializer $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $this->serializer = new Serializer([ new DateTimeNormalizer(), $normalizer]);
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    // REST-метод для добавления резюме
    #[Route('/api/cv/add', name: 'post_resume', methods: ['POST'])]
    public function postResume(Request $request, ResumeRepository $resumeRepository, EducationOptionRepository $optionRepository): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);

        $resume = new Resume();
        $resume = $resumeRepository->createResume($parameters, $resume);
        $resumeRepository->add($resume, true);

        if ($parameters['education']['institution'] != null) {
            $educationOption = new EducationOption();
            $educationOption = $optionRepository->createEducationOption($parameters['education'], $resume, $educationOption);
            $optionRepository->add($educationOption, true);
        }
        if ($resume->getEducation() !== 'Среднее') {
            $educationOption = $optionRepository->createEducationOption($parameters['education'], $resume, $educationOption);
            $optionRepository->add($educationOption, true);
            $educationOption = new EducationOption();
            if ($parameters['education']['secondEducationEnabled'] == true) {
                $educationOption = $optionRepository->createEducationOption($parameters['optional'], $resume, $educationOption);
                $optionRepository->add($educationOption, true);
            }
        }

        return new JsonResponse([
            'result' => $resume->getId(),
        ]);
    }
    // REST-метод для изменения резюме
    #[Route('/api/cv/{id}/edit', name: 'edit_resume', methods: ['POST'])]
    public function editResume(int $id, Request $request, ResumeRepository $resumeRepository, EducationOptionRepository $optionRepository): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);

        $resume = $resumeRepository->find($id);

        $resume = $resumeRepository->createResume($parameters, $resume);
        $resumeRepository->add($resume, true);

        if ($resume->getEducation() !== 'Среднее') {
            $educationOptions = $optionRepository->findBy(['resume' => $resume->getId()]);

            foreach ($educationOptions as $educationOption) {
                $optionRepository->remove($educationOption, true);
            }
            $educationOption = new EducationOption();
            $educationOption = $optionRepository->createEducationOption($parameters['education'], $resume, $educationOption);
            $optionRepository->add($educationOption, true);
            if ($parameters['education']['secondEducationEnabled'] == true) {
                $educationOption = $optionRepository->createEducationOption($parameters['optional'], $resume, $educationOption);
                $optionRepository->add($educationOption, true);
            }
        }

        return new JsonResponse([
            'result' => $resume->getId(),
        ]);
    }
    // REST-метод для изменения статуса резюме
    #[Route('/api/cv/{id}/status/update', name: 'edit_resume_status', methods: ['POST'])]
    public function editResumeStatus(int $id, Request $request, ResumeRepository $resumeRepository): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);

        var_dump($parameters);

        $resume = $resumeRepository->find($id);

        $resume->setStatus($parameters);

        $resumeRepository->add($resume, true);

        return new JsonResponse([
            'result' => $resume->getId(),
        ]);
    }


    /**
     * @throws ExceptionInterface
     */
    // REST-метод для получения данных о резюме
    #[Route('/api/cv/{id}', name: 'get_resume', methods: ['GET'])]
    public function getResume(int $id, ResumeRepository $resumeRepository, EducationOptionRepository $optionRepository): JsonResponse
    {
        $resume = $resumeRepository->find($id);

        $resultResume = $this->serializer->normalize($resume, null, ['groups' => ['resume']]);

        if ($resume && $resume->getEducation() !== 'Среднее') {
            $options = $optionRepository->findBy(
                ['resume' => $resume->getId()]
            );

            $resultOptions = $this->serializer->normalize($options, null, ['groups' => ['options']]);

            $resultResume['optional'] = $resultOptions;
        }

        return new JsonResponse([
            'result' => $resultResume
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    // REST-метод для получения списка резюме
    #[Route('/api/cv', name: 'get_resume_list', methods: ['GET'])]
    public function getResumeList(ResumeRepository $resumeRepository, EducationOptionRepository $optionRepository): JsonResponse
    {
        $resumeList = $resumeRepository->findAll();

        $resultResume = $this->serializer->normalize($resumeList, null, ['groups' => ['resume']]);

        for ($i = 0; $i < count($resumeList); $i++) {
            if ($resumeList[$i]->getEducation() !== 'Среднее') {
                $options = $optionRepository->findBy(
                    ['resume' => $resumeList[$i]->getId()]
                );

                $resultOptions = $this->serializer->normalize($options, null, ['groups' => ['options']]);

                $resultResume[$i]['optional'] = $resultOptions;
            }
        }

        return new JsonResponse([
            'result' => $resultResume,
        ]);
    }
}