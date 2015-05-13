<?php
namespace Jul\TestBundle\Controller;

use Jul\TestBundle\Entity\Advert;
use Jul\TestBundle\Entity\Image;
use Jul\TestBundle\Entity\Application;

use Jul\TestBundle\Form\AdvertType;
use Jul\TestBundle\Form\AdvertEditType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
		if ($page < 1) {
		  throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		}
		$nbPerPage = 3;
		$listAdverts = $this
			->getDoctrine()
			->getManager()
			->getRepository('JulTestBundle:Advert')
			->getAdverts($page,$nbPerPage)
		;
		$nbPages = ceil(count($listAdverts)/$nbPerPage);
		if ($page > $nbPages) {
			throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		}
		return $this->render('JulTestBundle:Advert:index.html.twig', array(
			'listAdverts' => $listAdverts,
			'nbPages' => $nbPages,
			'page' => $page
		));
	}
	
	public function viewAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$advert = $em->getRepository('JulTestBundle:Advert')->find($id);
		
		if (null === $advert) {
		  throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}
		
		$listAdvertSkills = $em
			->getRepository('JulTestBundle:AdvertSkill')
			->findByAdvert($advert)
		;
		return $this->render('JulTestBundle:Advert:view.html.twig', array(
		  'advert' => $advert,
		  'listAdvertSkills' => $listAdvertSkills
		));
	}

	public function menuAction($limit=3)
	{
		$listAdverts = $this
			->getDoctrine()
			->getManager()
			->getRepository('JulTestBundle:Advert')
			->findBy(
				array(),
				array('id' => 'desc'),
				$limit,
				0
			)
		;

		return $this->render('JulTestBundle:Advert:menu.html.twig', array(
		  'listAdverts' => $listAdverts
		));
	}
	
	public function addAction(Request $request)
	{	
		$advert = new Advert();
		$form = $this->createForm(new AdvertType(), $advert);
		
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($advert);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

			return $this->redirect($this->generateUrl('jul_platform_view', array(
				'id' => $advert->getId()
			)));
		}
		
		return $this->render('JulTestBundle:Advert:add.html.twig', array(
			'form' => $form->createView()
		));
	}

	public function editAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$advert = $em->getRepository('JulTestBundle:Advert')->find($id);
				
		if (null === $advert) {
		  throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}
		
		$form = $this->createForm(new AdvertEditType(), $advert);
		
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
			return $this->redirect($this->generateUrl('jul_platform_view', array(
				'id' => $advert->getId()
			)));
		}
		
		return $this->render('JulTestBundle:Advert:edit.html.twig', array(
			'form' => $form->createView(),
			'advert' => $advert
		));
	}

	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$advert = $em->getRepository('JulTestBundle:Advert')->find($id);

		if (null === $advert) {
		  throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}
		
		$form = $this->createFormBuilder()->getForm();
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$em->remove($advert);
			$em->flush();
			$request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');
			return $this->redirect($this->generateUrl('jul_platform_home'));
		}

		return $this->render('JulTestBundle:Advert:delete.html.twig', array(
			'form' => $form->createView(),
			'advert' => $advert
		));
	}
}
