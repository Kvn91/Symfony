<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Kevin\PlatformBundle\Controller;

use Kevin\PlatformBundle\Entity\Advert;
use Kevin\PlatformBundle\Entity\AdvertSkill;
use Kevin\PlatformBundle\Entity\Image;
use Kevin\PlatformBundle\Entity\Application;
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
        $em = $this->getDoctrine()->getManager();

        if($request->isMethod('POST')){
//            gestion du formulaire

            $request->getSession()->getFlashBag()->add('notice', 'Annonce enregistrée !');
            return $this->redirectToRoute('kevin_platform_view', ['id' => $id]);
        }
        return $this->render('KevinPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request)
    {
        $em                 = $this->getDoctrine()->getManager();

        $advert             = $em->getRepository('KevinPlatformBundle:Advert')->find($id);
        if(null === $advert){
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }

        if($request->isMethod('POST')){

            $request->getSession()->getFlashBag()->add('notice', 'Annonce modifiée !');
            return $this->redirectToRoute('kevin_platform_view', ['id' => $id]);
        }
        return $this->render('KevinPlatformBundle:Advert:edit.html.twig', ['advert' => $advert]);
    }

    public function deleteAction($id, Request $request)
    {
        $em             = $this->getDoctrine()->getManager();
        $advert         = $em->getRepository('KevinPlatformBundle:Advert')->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }

        if($request->isMethod('POST')){
//            Gestion de la suppression d'annonce

            $request->getSession()->getFlashBag()->add('notice', 'Annonce supprimée !');
            return $this->redirectToRoute('kevin_platform_home');
        }
        $this->render('KevinPlatformBundle:Advert:delete.html.twig', ['advert' => $advert]);
    }

    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();

        $advertsList        = $em->getRepository('KevinPlatformBundle:Advert')->findBy([], ['date' => 'desc'], $limit, 0);
        $applicationsList   = $em->getRepository('KevinPlatformBundle:Application')->getApplicationsWithAdvert($limit);

        return $this->render('KevinPlatformBundle:Advert:menu.html.twig', ['advertsList' => $advertsList, 'applicationsList' => $applicationsList]);
    }
}