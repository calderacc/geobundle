<?php

namespace Caldera\CriticalmassBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Caldera\CriticalmassBundle\Entity as Entity;

class LiveController extends Controller
{
	public function showAction($citySlug)
	{
		$city = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:CitySlug')->findOneBySlug($citySlug)->getCity();

		return $this->render('CalderaCriticalmassBundle:Live:show.html.twig', array('city' => $city));
	}

	public function trackpositionAction()
	{
		$query = $this->getRequest()->query;

		$position = new Entity\Position();

		$position->setUser($this->getDoctrine()->getRepository('CalderaCriticalmassBundle:User')->findOneById(103));
		$position->setRide($this->getDoctrine()->getRepository('CalderaCriticalmassBundle:Ride')->findOneById(242));

		$position->setLatitude($query->get("latitude") ? $query->get("latitude") : 0.0);
		$position->setLongitude($query->get("longitude") ? $query->get("longitude") : 0.0);
		$position->setAccuracy($query->get("accuracy") ? $query->get("accuracy") : 0.0);
		$position->setAltitude($query->get("altitude") ? $query->get("altitude") : 0.0);
		$position->setAltitudeAccuracy($query->get("altitudeaccuracy") ? $query->get("altitudeaccuracy") : 0.0);
		$position->setHeading($query->get("heading") ? $query->get("heading") : 0.0);
		$position->setSpeed($query->get("speed") ? $query->get("speed") : 0.0);
		$position->setTimestamp($query->get("timestamp") ? $request->request->get("timestamp") : 0);
		$position->setCreationDateTime(new \DateTime());

		$manager = $this->getDoctrine()->getManager();
		$manager->persist($position);
		$manager->flush();

		return new Response($position->getId());
	}
}