<?php

namespace App\Controller;

use App\Entity\Train;
use App\Entity\Destination;
use App\Form\TrainForm;
use App\Repository\TrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TrainController extends AbstractController
{
    /**
     * @Route("/train/add", name="train_add")
     */
    public function addTrain(ValidatorInterface $validator,Request $request): Response
    {
        $train = new Train();
        $form = $this->createForm(TrainForm::class, $train, []);

        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $train = $form->getData();

             $errors = $validator->validate($train);
             if (count($errors) > 0) {
                 return new Response((string) $errors, 400);
             }

             $entityManager->persist($train);
             $entityManager->flush();

             return $this->redirectToRoute('show_all');
         }

        return $this->render('new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/train/show/{id}", name="train_show")
     */
    public function show(int $id): Response
    {
        $train = $this->getDoctrine()
            ->getRepository(Train::class)
            ->find($id);

        if (!$train) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $stringTrain = '';
        $stringTrain = $stringTrain.'<br/>'.$train->getIdtrain().' '.$train->getName().' '.$train->getPrice();

        return new Response('Check out this train: '.$stringTrain);
    }

    /**
     * @Route("/train/show_all", name="show_all")
     */
    public function show_all(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trains = $this->getDoctrine()
                ->getRepository(Train::class)
                ->findAll();

        if (!$trains) {
            throw $this->createNotFoundException(
                'No train found for id '
            );
        }

        $stringTrains = '';
        foreach ($trains as $trainnn){
            $stringTrains = $stringTrains.'<br/>'.$trainnn->getIdtrain().' '.$trainnn->getName().' '.$trainnn->getPrice();
        }

        return new Response('Check out this trains: '.$stringTrains);
    }

    /**
     * @Route("/train/edit/{id}")
     */
    public function update(int $id, Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $train = $entityManager->getRepository(Train::class)->find($id);

        if (!$train) {
            throw $this->createNotFoundException(
                'No train found for id '.$id
            );
        }
        $form = $this->createForm(TrainForm::class, $train, []);

        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $train = $form->getData();
             $errors = $validator->validate($train);
             if (count($errors) > 0) {
                 return new Response((string) $errors, 400);
             }
             $entityManager->flush();

             return $this->redirectToRoute('show_all');
         }

        return $this->render('new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/train/delete/{id}")
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $train = $entityManager->getRepository(Train::class)->find($id);

        if (!$train) {
            throw $this->createNotFoundException(
                'No train found for id '.$id
            );
        }

        $entityManager->remove($train);
        $entityManager->flush();


        return $this->redirectToRoute('show_all',[]);
    }
}
