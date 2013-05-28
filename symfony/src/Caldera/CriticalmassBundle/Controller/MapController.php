<?php

namespace Caldera\CriticalmassBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Caldera\CriticalmassBundle\Entity as Entity;

class MapController extends Controller
{
	public function mapcenterAction()
	{
		$response = new Response();
		$response->setContent(json_encode(array(
			'latitude' => 53.57033623530256,
			'longitude' => 9.719623122674422
		)));

		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}
