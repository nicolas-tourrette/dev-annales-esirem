<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername("nt780325");
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '&siremDijon21'
        ));
        $user->setName("Nicolas TOURRETTE");
        $user->setEmail("nicolas_tourrette@etu.u-bourgogne.fr");
        $user->setRoles(array("ROLE_ADMIN"));
        $user->setBirthday(new \Datetime("1998-08-12"));
        $user->setPublic(true);
        $user->setProfilimage("/img/user.png");

        $manager->persist($user);
        $manager->flush();
    }
}
