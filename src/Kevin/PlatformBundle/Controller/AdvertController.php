<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Kevin\PlatformBundle\Controller;

use Kevin\PlatformBundle\Entity\Advert;
use Kevin\PlatformBundle\Entity\Image;
use Kevin\PlatformBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $doctrine = $this->getDoctrine();

//        return $this->render('KevinPlatformBundle:Advert:index.html.twig', ['advertsList' => $advertsList]);
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $advertsRep = $em->getRepository('KevinPlatformBundle:Advert');
        $advert = $advertsRep->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }

        $applicationsRep = $em->getRepository('KevinPlatformBundle:Application');
        $applicationsList = $applicationsRep->findBy(['advert' => $advert]);

        return $this->render('KevinPlatformBundle:Advert:view.html.twig', [
            'advert' => $advert,
            'applicationsList' => $applicationsList
        ]);
    }

    public function addAction(Request $request)
    {
        // Créer une annonce
        $advert = new Advert;
        $advert->setAuthor('Kevin')
            ->setContent('blabla')
            ->setDate(new \DateTime)
            ->setPublished(0)
            ->setTitle('Hello Title 2');

        // Créer une image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg')
                ->setAlt('Job de rêve');

        $advert->setImage($image);

        // Création d'une candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        // Création d'une deuxième candidature
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        // Lier les candidatures à l'annonce
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $em = $this->getDoctrine()->getManager();

        $em->persist($advert);
        $em->persist($application1);
        $em->persist($application2);

        $em->flush();

        if($request->isMethod('POST')){
//            gestion du formulaire

            $id = 5;
            $request->getSession()->getFlashBag()->add('notice', 'Annonce enregistrée !');
            return $this->redirectToRoute('kevin_platform_view', ['id' => $id]);
        }

        return $this->render('KevinPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request)
    {
        $advert = array(
            'title'   => 'Recherche développpeur Symfony2',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        if($request->isMethod('POST')){
//            Gestion de la modification d'annonce

            $request->getSession()->getFlashBag()->add('notice', 'Annonce modifiée !');
            return $this->redirectToRoute('kevin_platform_view', ['id' => $id]);
        }

        return $this->render('KevinPlatformBundle:Advert:edit.html.twig', ['advert' => $advert]);
    }

    public function deleteAction($id, Request $request)
    {
        if($request->isMethod('POST')){
//            Gestion de la suppression d'annonce

            $request->getSession()->getFlashBag()->add('notice', 'Annonce supprimée !');
            return $this->redirectToRoute('kevin_platform_home');
        }

        $this->render('KevinPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction($limit)
    {
        $advertsList = [['id' => 1, 'title' => 'Annonce 1'],
                        ['id' => 2, 'title' => 'Annonce 2'],
                        ['id' => 3, 'title' => 'Annonce 3']
                        ];

        return $this->render('KevinPlatformBundle:Advert:menu.html.twig', ['advertsList' => $advertsList]);
    }
}