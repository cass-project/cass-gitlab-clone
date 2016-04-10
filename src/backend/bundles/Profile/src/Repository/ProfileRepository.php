<?php
namespace Profile\Repository;

use Account\Entity\Account;
use Common\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Profile\Entity\Profile;
use Profile\Entity\ProfileGreetings;
use Profile\Entity\ProfileImage;

class ProfileRepository extends EntityRepository
{
    public function attachProfileToAccount(Profile $profile, Account $account): Profile
    {
        $this->getEntityManager()->persist($profile);

        return $profile;
    }

    public function createProfile(Profile $profile): Profile
    {
        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush($profile);

        return $profile;
    }

    public function deleteProfile(Profile $profile)
    {
        $this->getEntityManager()->remove($profile);
        $this->getEntityManager()->flush($profile);
    }

    public function getProfileById(int $profileId): Profile
    {
        $result = $this->find($profileId);

        if($result === null) {
            throw new EntityNotFoundException("Entity with ID {$profileId} not found");
        }

        return $result;
    }

    public function nameFL(int $profileId, string $firstName, string $lastName)
    {
        $em = $this->getEntityManager();

        $greetings = $this->getProfileById($profileId)->getProfileGreetings();
        $greetings
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setGreetingsMethod(ProfileGreetings::GREETINGS_FL);

        $em->persist($greetings);
        $em->flush($greetings);
    }

    public function nameLFM(int $profileId, string $lastName, string $firstName, string $middleName)
    {
        $em = $this->getEntityManager();

        $greetings = $this->getProfileById($profileId)->getProfileGreetings();
        $greetings
            ->setLastName($lastName)
            ->setFirstName($firstName)
            ->setMiddleName($middleName)
            ->setGreetingsMethod(ProfileGreetings::GREETINGS_LFM);

        $em->persist($greetings);
        $em->flush($greetings);
    }

    public function nameN(int $profileId, string $nickName)
    {
        $em = $this->getEntityManager();

        $greetings = $this->getProfileById($profileId)->getProfileGreetings();
        $greetings
            ->setNickName($nickName)
            ->setGreetingsMethod(ProfileGreetings::GREETINGS_N);

        $em->persist($greetings);
        $em->flush($greetings);
    }

    public function updateImage(int $profileId, string $storagePath, string $publicPath): ProfileImage
    {
        $em = $this->getEntityManager();

        $image = $this->getProfileById($profileId)->getProfileImage();
        $image
            ->setStoragePath($storagePath)
            ->setPublicPath($publicPath);

        $em->persist($image);
        $em->flush($image);

        return $image;
    }
}