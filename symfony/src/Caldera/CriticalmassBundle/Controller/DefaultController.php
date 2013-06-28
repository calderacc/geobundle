<?php

namespace Caldera\CriticalmassBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Caldera\CriticalmassBundle\Entity as Entity;

class DefaultController extends Controller
{
	/**
	 * Zeigt eine Liste der Critical-Mass-Touren in der Umgebung an.
	 *
	 * @param $latitude Breitengrad des Suchpunktes
	 * @param $longitude Längengrad des Suchpunktes
	 */
	public function choosecityAction($latitude, $longitude)
	{
		$cityResults = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:City')->findNearestedByLocation($latitude, $longitude);

		foreach ($cityResults as $key => $result)
		{
			$cityResults[$key]['ride'] = $this->get('caldera_criticalmass_ride_repository')->findOneBy(array('city' => $cityResults[$key]['city']->getId()));
			$cityResults[$key]['distance'] = $this->get('caldera_criticalmass_citydistancecalculator')->calculateDistanceFromCoordToCoord($cityResults[$key]['city']->getLatitude(), $latitude, $cityResults[$key]['city']->getLongitude(), $longitude);
		}
	
		return $this->render('CalderaCriticalmassBundle:Default:choosecity.html.twig', array('cityResults' => $cityResults));
	}

	/**
	 * Ruft ein Template auf, dass per JavaScript die Position des Endgerätes
	 * ausliest und an die nächste Action weiterleitet.
	 */
	public function selectcityAction()
	{
		return $this->render('CalderaCriticalmassBundle:Default:selectcity.html.twig');
	}

	/**
	 * Lädt die angegebene Stadt aus der Datenbank und reicht sie an das Template zur Anzeige weiter.
	 *
	 * @param $city Name der Stadt
	 */
	public function showcityAction($city)
	{
		// aufzurufende Stadt anhand des Slugs ermitteln
		$citySlug = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:CitySlug')->findOneBySlug($city);

		// wurde die Stadt überhaupt gefunden?
		if (empty($citySlug))
		{
			// Fehlermeldung werfen
			throw $this->createNotFoundException('This city does not exist');
		}
		else
		{
			// Stadt anhand des übergebenen Parameters laden
			$city = $citySlug->getCity();

			$ride = $this->get('caldera_criticalmass_ride_repository')->findOneBy(array('city' => $city->getId()), array('date' => 'DESC'));

			// Darstellung an das Template weiterreichen
			return $this->render('CalderaCriticalmassBundle:Default:index.html.twig', array('city' => $city, 'ride' => $ride));
		}
	}
}
