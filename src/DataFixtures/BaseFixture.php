<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of BaseFixture
 *
 * @author Juan Ramírez
 */
abstract class BaseFixture extends Fixture {

    /** @var ObjectManager */
	private $manager;

	/** @var Generator */
	protected $faker;
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    abstract protected function loadData(ObjectManager $manager);

	public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

	public function load(ObjectManager $manager){

	    $this->manager = $manager;
		$this->faker = Factory::create();

		$this->loadData($manager);

	}
	
	protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }
}
