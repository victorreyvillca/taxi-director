<?php
use Model\Area;
use Model\Position;
use Model\Mission;
use Model\Region;
use Model\District;
use Model\Church;
use Model\Club;
use Model\Picture;
use Model\PictureNews;

/**
 * Controller for DIST 2.
 *
 * @category Dist 2
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_IndexController extends Dis_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
 	public function init() {
 		parent::init();
		$uri = $this->_request->getPathInfo();
    }

	public function indexAction() {
//         $position = new Position();
//         $position->setName('name')->setDescription('des')->setCreated(new DateTime('now'))->setState(TRUE);

//         $this->_entityManager->persist($position);
//         $this->_entityManager->flush();


//         $mission = new Mission();
//         $mission->setName('name')->setAbreviation('MOB')->setDescription('des')->setCreated(new DateTime('now'))->setState(TRUE);

//         $this->_entityManager->persist($mission);
//         $this->_entityManager->flush();


//         $region = new Region();
//         $region->setName('name')->setAbreviation('MOB')->setDescription('des')->setMission($mission)->setCreated(new DateTime('now'))->setState(TRUE);

//         $this->_entityManager->persist($region);
//         $this->_entityManager->flush();


//         $district = new District();
//         $district->setName('Nuevo Palmar')->setDescription('')->setRegion($region)->setCreated(new DateTime('now'))->setState(TRUE);

//         $this->_entityManager->persist($district);
//         $this->_entityManager->flush();

//         $church = new Church();
//         $church->setName('Nuevo Palmar')->setDescription('')->setDistrict($district)->setCreated(new DateTime('now'))->setState(TRUE);

//         $this->_entityManager->persist($church);
//         $this->_entityManager->flush();

//         $area = new Area();
//         $area->setName('Conquistador')->setDescription('fd')->setCreated(new DateTime('now'))->setState(TRUE);

//         $this->_entityManager->persist($area);
//         $this->_entityManager->flush();

//         $club = new Club();
//         $club->setArea($area)->setChurch($church)->setName('Orion Santa Cruz')->setState(TRUE)->setCreated(new DateTime('now'));

//         $this->_entityManager->persist($club);
//         $this->_entityManager->flush();

//         $picture = new Picture();
//         $picture->setTitle('my title')->setDescription('des')->setFilename('filename')->setMimeType('mimitype')->setSrc('src')->setState(TRUE)->setCreated(new DateTime('now'))->setCreatedBy(1);

//         $this->_entityManager->persist($picture);
//         $this->_entityManager->flush();

//         $news = $this->_entityManager->find('Model\News', 1);
//         $pictureNews = new PictureNews();
//         $pictureNews->setNews($news)->setTitle('my title')->setFilename('filename')->setMimeType('mimitype')->setSrc('src')->setState(TRUE)->setCreated(new DateTime('now'))->setCreatedBy(1);

//         $this->_entityManager->persist($pictureNews);
//         $this->_entityManager->flush();

//         $directive = new Directive();
//         $directive->setTreatment('super mal')->setRanks('Guia mayor')->setPositions('Director')->setYear(22)->setIsActivo(TRUE)->setState(TRUE)->setCreated(new DateTime('now'))->setSex(1)->setPhonemobil(465456)->setPhonework('45646') ->setPhone('45645') ->setDateOfBirth(new DateTime('now')) ->setIdentityCard(59387823)->setLastName('Villca')->setFirstName('Joel');

//         $this->_entityManager->persist($directive);
//         $this->_entityManager->flush();


	}
}