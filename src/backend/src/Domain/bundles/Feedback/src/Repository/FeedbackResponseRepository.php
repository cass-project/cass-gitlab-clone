<?php
namespace CASS\Domain\Bundles\Feedback\Repository;

use Doctrine\ORM\EntityRepository;
use CASS\Domain\Bundles\Feedback\Entity\Feedback;
use CASS\Domain\Bundles\Feedback\Entity\FeedbackResponse;
use CASS\Domain\Bundles\Feedback\Exception\FeedbackNotFoundException;

class FeedbackResponseRepository extends EntityRepository
{
    public function createFeedbackResponse(FeedbackResponse $feedbackResponse)
    {
        $this->getEntityManager()->persist($feedbackResponse);
        $this->getEntityManager()->flush([$feedbackResponse]);
    }

    public function saveFeedbackResponse(FeedbackResponse $feedbackResponse)
    {
        $this->getEntityManager()->flush([$feedbackResponse]);
    }

    public function getFeedbackResponses(int $feedbackId):array
    {
        return $this->findBy(['feedback' => $this->getFeedbackById($feedbackId)]);
    }

    public function getFeedbackById(int $feedbackId): Feedback
    {
        $feedback = $this->getEntityManager()->getRepository(Feedback::class)->find($feedbackId);

        if(is_null($feedback)) {
            throw new FeedbackNotFoundException(sprintf("Feedback: %s not found", $feedbackId));
        }

        return $feedback;
    }
}