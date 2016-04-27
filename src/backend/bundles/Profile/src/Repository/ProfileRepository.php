<?php
namespace Profile\Repository;

use Account\Entity\Account;
use Common\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Profile\Entity\Profile;
use Profile\Entity\ProfileGreetings;
use Profile\Entity\ProfileImage;
use Profile\Exception\NoThemesToMerge;
use Profile\Exception\ProfileNotFoundException;
use Profile\Middleware\Parameters\ExpertInParameters;
use Theme\Entity\Theme;

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
            throw new ProfileNotFoundException("Entity with ID {$profileId} not found");
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

    public function setAsInitialized(int $profileId)
    {
        $em = $this->getEntityManager();

        $profile = $this->getProfileById($profileId);
        $profile->setAsInitialized();

        $em->persist($profile);
        $em->flush($profile);
    }

    public function switchTo(array $profiles, Profile $profile)
    {
        $em = $this->getEntityManager();

        /** @var Profile[] $profiles */
        foreach($profiles as $compare) {
            if($compare->getId() === $profile->getId()) {
                $compare->setIsCurrent(true);
            }else{
                $compare->setIsCurrent(false);
            }

            $em->persist($compare);
        }

        $em->flush($profiles);
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

    public function updateProfile(Profile $profile)
    {
        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();
    }

    public function setExpertsInParameters(int $profileId, ExpertInParameters $expertInParameters): Profile
    {
        /** @var Profile $profile */
        $profile = $this->getProfileById($profileId);

        // получаем темы по ids
        $themes = $this->getEntityManager()->getRepository(Theme::class)->findBy(
          ['id' => $expertInParameters->getThemeIds()]
        );

        $profile->setExpertIn($themes)
          ->setExpertInIds($profile->getExpertIn());

        $this->updateProfile($profile);

        return $profile;
    }

    public function mergeExpertsInParameters(int $profileId, ExpertInParameters $expertInParameters): Profile
    {
        /** @var Profile $profile */
        $profile = $this->getProfileById($profileId);

        // получаем темы по ids
        $themes = $this->getEntityManager()->getRepository(Theme::class)->findBy(
          ['id' => $expertInParameters->getThemeIds()]
        );

        // removing exist themes
        foreach($themes as $k => $theme){
            foreach($profile->getExpertIn()->toArray() as $profile_theme){
                if($theme->getId() == $profile_theme->getId()) {
                    unset($themes[$k]);
                    continue(2);
                }
            }
        }

        if(count($themes)==0) throw new NoThemesToMerge("there is no new themes to add to interestIn");

        foreach($themes as $theme){
            $profile->getExpertIn()->add($theme);
        }

        $profile->setExpertInIds($profile->getExpertIn()->toArray());

        $this->updateProfile($profile);
        return $profile;
    }

    public function getProfileWithExpertsIn(int $profileId): Profile
    {
        return $this->find($profileId);
    }
}