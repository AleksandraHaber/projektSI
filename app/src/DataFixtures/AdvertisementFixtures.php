<?php

/**
 * Task fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
//use App\Entity\Enum\TaskStatus;

use App\Entity\Advertisement;
//use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


/**
 * Class AdvertisementFixtures.
 */
class AdvertisementFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'advertisements', function (int $i) {
            $advertisement = new Advertisement();
            $advertisement->setTitle($this->faker->sentence);
            $advertisement->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $advertisement->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $advertisement->setCategory($category);
            $advertisement->setText($this->faker->sentence);
            $advertisement->setIsActive(true);
            $advertisement->setPhoneNumber($this->faker->randomNumber(9,true));
            $advertisement->setEmail($this->faker->email);

            return $advertisement;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
