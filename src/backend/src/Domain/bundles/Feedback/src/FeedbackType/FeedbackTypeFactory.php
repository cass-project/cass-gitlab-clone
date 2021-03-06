<?php
namespace CASS\Domain\Bundles\Feedback\FeedbackType;

use CASS\Domain\Bundles\Feedback\Exception\InvalidFeedbackTypeException;
use CASS\Domain\Bundles\Feedback\FeedbackType\Types\FTCommonQuestion;
use CASS\Domain\Bundles\Feedback\FeedbackType\Types\FTSuggestion;
use CASS\Domain\Bundles\Feedback\FeedbackType\Types\FTThemeRequest;

final class FeedbackTypeFactory
{
    public function createFromIntCode(int $code): FeedbackType
    {
        switch($code) {
            default:
                throw new InvalidFeedbackTypeException(sprintf('Invalid feedback type `%s`', $code));

            case FTCommonQuestion::INT_CODE: return new FTCommonQuestion();
            case FTSuggestion::INT_CODE: return new FTSuggestion();
            case FTThemeRequest::INT_CODE: return new FTThemeRequest();
        }
    }

    public function listCodes(): array
    {
        return [
            FTCommonQuestion::INT_CODE,
            FTSuggestion::INT_CODE,
            FTThemeRequest::INT_CODE,
        ];
    }
}