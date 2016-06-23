<?php
namespace Domain\Profile\Events;

use Application\Events\EventsBootstrapInterface;
use Domain\Profile\Entity\Profile;
use Domain\Profile\Entity\ProfileExpertInEQ;
use Domain\Profile\Repository\ProfileExpertInEQRepository;
use Domain\Profile\Repository\ProfileRepository;
use Domain\Profile\Service\ProfileService;
use Domain\Theme\Entity\Theme;
use Domain\Theme\Service\ThemeService;
use Evenement\EventEmitterInterface;

final class ProfileExpertInEQEvents implements EventsBootstrapInterface
{
    /** @var ThemeService */
    private $themeService;

    /** @var ProfileService */
    private $profileService;

    /** @var ProfileRepository */
    private $profileRepository;

    /** @var ProfileExpertInEQRepository */
    private $eq;

    public function __construct(
        ThemeService $themeService,
        ProfileService $profileService,
        ProfileRepository $profileRepository,
        ProfileExpertInEQRepository $eq
    ) {
        $this->themeService = $themeService;
        $this->profileService = $profileService;
        $this->profileRepository = $profileRepository;
        $this->eq = $eq;
    }

    public function up(EventEmitterInterface $globalEmitter)
    {
        $eq = $this->eq;
        $profileService = $this->profileService;
        $themeService = $this->themeService;

        $themeService->getEventEmitter()->on(ThemeService::EVENT_DELETE, function(Theme $theme) use ($eq, $profileService) {
            array_reduce($eq->getProfilesByThemeId($theme->getId()), function(ProfileExpertInEQ $eq) use ($profileService) {
                $profile = $profileService->getProfileById($eq->getProfileId());
                $profile->setExpertInIds(array_filter($profile->getExpertInIds(), function($input) use ($eq) {
                    return (int) $input !== (int) $eq->getThemeId();
                }));

                $this->profileRepository->saveProfile($profile);
            });

            $eq->deleteEQOfTheme($theme->getId());
        });

        $profileService->getEventEmitter()->on(ProfileService::EVENT_PROFILE_CREATED, function(Profile $profile) use ($eq) {
            $eq->sync($profile->getId(), $profile->getExpertInIds());
        });

        $profileService->getEventEmitter()->on(ProfileService::EVENT_PROFILE_UPDATED, function(Profile $profile) use ($eq) {
            $eq->sync($profile->getId(), $profile->getExpertInIds());
        });

        $profileService->getEventEmitter()->on(ProfileService::EVENT_PROFILE_DELETE, function(Profile $profile) use ($eq) {
            $eq->deleteEQOfProfile($profile->getId());
        });
    }
}