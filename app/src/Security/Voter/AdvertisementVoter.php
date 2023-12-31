<?php
/**
 * Advertisement voter.
 */

namespace App\Security\Voter;

use App\Entity\Advertisement;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AdvertisementVoter.
 */
class AdvertisementVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @const string
     */
    public const EDIT = 'POST_EDIT';

    /**
     * View permission.
     *
     * @const string
     */
    public const VIEW = 'POST_VIEW';

    /**
     * Delete permission.
     *
     * @const string
     */
    public const DELETE = 'POST_DELETE';

    /**
     * Security helper.
     */
    private Security $security;

    /**
     * OrderVoter constructor.
     *
     * @param Security $security Security helper
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool Result
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Advertisement;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Permission name
     * @param mixed          $subject   Object
     * @param TokenInterface $token     Security token
     *
     * @return bool Vote result
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    /**
     * Checks if user can edit task.
     *
     * @param Advertisement $advertisement Task entity
     * @param User          $user          User
     *
     * @return bool Result
     */
    private function canEdit(Advertisement $advertisement, User $user): bool
    {
        return isset($user);
    }

    /**
     * Checks if user can view task.
     *
     * @param User $user User
     *
     * @return bool Result
     */
    private function canView(User $user): bool
    {
        return isset($user);
    }

    /**
     * Checks if user can delete task.
     *
     * @param User $user User
     *
     * @return bool Result
     */
    private function canDelete(User $user): bool
    {
        return isset($user);
    }
}
