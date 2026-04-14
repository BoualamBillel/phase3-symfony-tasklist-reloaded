<?php
namespace App\Security\Voter;
use App\Entity\User;
use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;


class TaskVoter extends Voter
{
    public const VIEW = 'TASK_VIEW';
    public const EDIT = 'TASK_EDIT';
    public const DELETE = 'TASK_DELETE';


    /**
     * This method determines if the voter supports the given attribute and subject. If it returns false, the voteOnAttribute() method is not called.
     * @param mixed $attribute 
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], )
            && $subject instanceof Task;
    }

    /**
     * If this method is called, it means that supports() returned true. Now we check if the user has the right to do the action on the object
     * @param mixed $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @param ?Vote $vote
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null) : bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $task = $subject;

        return $task->getOwner() === $user;
    }
}

?>