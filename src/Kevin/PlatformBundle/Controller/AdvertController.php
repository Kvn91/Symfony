<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Kevin\PlatformBundle\Controller;

use Kevin\PlatformBundle\Entity\Advert;
use Kevin\PlatformBundle\Entity\AdvertSkill;
use Kevin\PlatformBundle\Entity\Image;
use Kevin\PlatformBundle\Entity\Application;
use Kevin\PlatformBundle\Form\AdvertEditType;
use Kevin\PlatformBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $em = $this->getDoctrine()->getManager();
        $advertsList = $em->getRepository('KevinPlatformBundle:Advert')->getAdverts($page);

        $nbPages = ceil(count($advertsList)/advert::NB_ADVERTS_PER_PAGE);

        if($page > $nbPages){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        return $this->render('KevinPlatformBundle:Advert:index.html.twig', [
            'advertsList' => $advertsList,
            'page' => $page,
            'nbPages' => $nbPages
        ]);
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('KevinPlatformBundle:Advert')->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }

        $applicationsList = $em->getRepository('KevinPlatformBundle:Application')->findByAdvert($advert);
        $advertSkillsList = $em->getRepository('KevinPlatformBundle:AdvertSkill')->findByAdvert($advert);

        return $this->render('KevinPlatformBundle:Advert:view.html.twig', [
            'advert'            => $advert,
            'applicationsList'  => $applicationsList,
            'advertSkillsList'  => $advertSkillsList
        ]);
    }

    public function addAction(Request $request)
    {
        $advert = new Advert();

        $form = $this->createForm(AdvertType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            var_dump($advert->getCategories());exit;
            $em->persist($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('kevin_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('KevinPlatformBundle:Advert:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $em                 = $this->getDoctrine()->getManager();

        $advert             = $em->getRepository('KevinPlatformBundle:Advert')->find($id);
        if(null === $advert){
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }
        var_dump($advert->getApplications());exit;

        $form = $this->createForm(AdvertEditType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce modifiée !');
            return $this->redirectToRoute('kevin_platform_view', array(
                'id' => $id
            ));
        }
        return $this->render('KevinPlatformBundle:Advert:edit.html.twig', [
            'advert' => $advert,
            'form'   => $form->createView()
        ]);
    }

    public function deleteAction($id, Request $request)
    {
        $em             = $this->getDoctrine()->getManager();
        $advert         = $em->getRepository('KevinPlatformBundle:Advert')->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
//            Gestion de la suppression d'annonce
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce supprimée !');
            return $this->redirectToRoute('kevin_platform_home');
        }
        return $this->render('KevinPlatformBundle:Advert:delete.html.twig', [
            'advert' => $advert,
            'form'   => $form->createView()
        ]);
    }

    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();

        $advertsList        = $em->getRepository('KevinPlatformBundle:Advert')->findBy([], ['date' => 'desc'], $limit, 0);
        $applicationsList   = $em->getRepository('KevinPlatformBundle:Application')->getApplicationsWithAdvert($limit);

        return $this->render('KevinPlatformBundle:Advert:menu.html.twig', ['advertsList' => $advertsList, 'applicationsList' => $applicationsList]);
    }

    public function testAction()
    {
        $advert = new Advert;

        $advert->setDate(new \Datetime());  // Champ « date » OK
        $advert->setTitle('abc');           // Champ « title » incorrect : moins de 10 caractères
        //$advert->setContent('blabla');    // Champ « content » incorrect : on ne le définit pas
        $advert->setAuthor('A');            // Champ « author » incorrect : moins de 2 caractères

        // On récupère le service validator
        $validator = $this->get('validator');

        // On déclenche la validation sur notre object
        $listErrors = $validator->validate($advert);

        // Si $listErrors n'est pas vide, on affiche les erreurs
        if(count($listErrors) > 0) {
            // $listErrors est un objet, sa méthode __toString permet de lister joliement les erreurs
            return new Response((string) $listErrors);
        } else {
            return new Response("L'annonce est valide !");
        }
    }
}