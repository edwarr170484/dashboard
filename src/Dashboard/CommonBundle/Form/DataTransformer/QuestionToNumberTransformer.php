<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class QuestionToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($question)
    {
        if (null === $question) {
            return '';
        }

        return $question->getId();
    }
    
    public function reverseTransform($questionId)
    {
        if (!$questionId) {
            return;
        }

        $question = $this->manager->getRepository('DashboardCommonBundle:Question')->find($questionId);
        
        if (null === $question) {
            
            throw new TransformationFailedException(sprintf(
                'Question with questionId "%s" does not exist!',
                $questionId
            ));
        }

        return $question;
    }
}