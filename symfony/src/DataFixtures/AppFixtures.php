<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Create one user
         */
        $user = new User();
        $user->setFullName("Admin");
        $user->setMail("admin@snowtricks.com");
        $user->setCreatedAt(new \DateTime());
        $user->setModifiedAt(new \DateTime());
        $password = $this->encoder->encodePassword($user, 'admin');
        $user->setPassword($password);
        $user->setActive(true);
        $manager->persist($user);

        /**
         * Create Category
         */
        $categoryGrabs = new Category();
        $categoryGrabs->setName('grabs');
        $manager->persist($categoryGrabs);

        $categoryRotation = new Category();
        $categoryRotation->setName('rotation');
        $manager->persist($categoryRotation);

        $categoryFlip = new Category();
        $categoryFlip->setName('flip');
        $manager->persist($categoryFlip);

        /**
         * First trick
         */
        $trick = new Trick();
        $trick->setName('Grabs Mute');
        $trick->setDescribing(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sollicitudin leo ex, blandit porttitor nisl lacinia non. In ultrices purus et odio tincidunt dapibus. Pellentesque faucibus blandit massa, et faucibus augue dictum eu. Fusce nec purus est. Sed et lacus ultrices, fermentum risus nec, tempus risus. Nunc pellentesque molestie convallis. In id efficitur ex. Aliquam eros odio, suscipit non tortor quis, iaculis mollis felis.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryGrabs);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * Second trick
         */
        $trick = new Trick();
        $trick->setName('Grabs sad');
        $trick->setDescribing(
            'Curabitur sit amet dignissim purus. Duis vel auctor augue, eget euismod quam. Nulla consequat laoreet diam nec tempus. Vivamus ipsum nibh, fringilla ac massa a, imperdiet varius urna. Sed euismod consectetur dui id bibendum. Cras tortor sapien, fermentum eget facilisis in, faucibus ut nisl. Nunc euismod dapibus justo, varius hendrerit mi scelerisque eu. Mauris congue porttitor blandit.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryGrabs);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * Third trick
         */
        $trick = new Trick();
        $trick->setName('Grabs indy');
        $trick->setDescribing(
            'Pellentesque in magna quis eros pretium faucibus. Ut non nunc erat. Donec suscipit metus tempus, ultricies massa et, rhoncus nibh. Nullam eleifend arcu sed augue dictum feugiat. Maecenas malesuada feugiat ante in rhoncus. Morbi accumsan, erat non ullamcorper cursus, nisl neque cursus nibh, non facilisis dui elit at dolor. Duis auctor magna quis eros pellentesque, vitae vehicula ipsum hendrerit. Phasellus sapien magna, tempus id erat id, iaculis bibendum arcu.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryGrabs);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * 4th trick
         */
        $trick = new Trick();
        $trick->setName('Grabs stalefish');
        $trick->setDescribing(
            'Aenean tellus ante, hendrerit vel porta non, aliquam quis diam. Maecenas faucibus rutrum vehicula. Duis sollicitudin lacus id augue tristique porttitor. Cras ultricies dolor ligula, eu laoreet ante aliquet sit amet. Quisque mattis, odio et congue dignissim, tortor purus luctus lectus, a vestibulum diam eros vel nulla. Proin posuere leo euismod, commodo sem ut, malesuada ligula. Donec congue purus lacus, sit amet vestibulum lacus laoreet vitae. Praesent lobortis eu sapien ut tempus.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryGrabs);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * 5th trick
         */
        $trick = new Trick();
        $trick->setName('Grabs japan');
        $trick->setDescribing(
            'Suspendisse vitae libero nec libero efficitur euismod. Sed neque tellus, auctor non ultricies ac, accumsan nec magna. Vivamus finibus auctor vulputate. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis ut facilisis magna, in tincidunt justo. Cras tristique ligula imperdiet ante porttitor aliquet eget at risus. Curabitur eu orci mattis, tempus nisi nec, condimentum diam. Proin mattis faucibus lacus, et porta justo. Praesent sem neque, porta ut pretium ut, auctor vitae ante.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryGrabs);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * 6th trick
         */
        $trick = new Trick();
        $trick->setName('360');
        $trick->setDescribing(
            'Integer enim sapien, aliquet et lectus in, viverra consequat felis. In porttitor faucibus felis vitae venenatis. Nulla pulvinar varius ullamcorper. Nullam a felis sem. Aliquam a felis laoreet, posuere neque in, tincidunt lacus. Aliquam commodo, dolor id commodo tincidunt, mi quam euismod est, ut ultricies eros lacus non magna. Aliquam ornare ex vitae neque accumsan, sed pharetra enim sodales. Phasellus egestas lacus a congue posuere.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryRotation);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * 7th trick
         */
        $trick = new Trick();
        $trick->setName('Front flips');
        $trick->setDescribing(
            'Aenean magna velit, consequat at elit nec, lacinia sodales leo. Phasellus eget lacus odio. Cras vel ante sit amet arcu semper interdum vel vitae enim. Proin justo nulla, suscipit non elit sed, porttitor scelerisque neque. Nunc gravida condimentum enim. Phasellus dignissim risus in laoreet vestibulum. Cras consequat at purus sed faucibus. Fusce nec ante venenatis, facilisis ante quis, accumsan enim.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryFlip);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * 8th trick
         */
        $trick = new Trick();
        $trick->setName('1080');
        $trick->setDescribing(
            'Cras accumsan finibus lobortis. Nam nec posuere ipsum. Nam vitae ligula sapien. Aenean nulla metus, imperdiet in ipsum eu, mollis semper augue. Aenean dictum ut lectus non egestas. Donec a nulla sit amet enim varius tempus. Vestibulum tincidunt, dolor vel egestas pulvinar, quam lectus rhoncus turpis, faucibus porta tellus neque quis quam. Curabitur erat tellus, fermentum non lacus non, porttitor elementum neque. Proin nec maximus libero. Praesent pulvinar ex cursus, venenatis tellus vel, lobortis urna. Praesent viverra eu ante et pharetra.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryRotation);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * 9th trick
         */
        $trick = new Trick();
        $trick->setName('450');
        $trick->setDescribing(
            'Sed vel tellus commodo, euismod magna a, iaculis dolor. Donec non posuere lacus, in aliquam nisi. Pellentesque eu pharetra lacus. Nunc sit amet metus nec eros luctus facilisis. Pellentesque pharetra nunc non fringilla consectetur. Pellentesque porta mi quis nulla commodo placerat. Nunc faucibus nisl vitae arcu sagittis, id mollis arcu vestibulum. Integer tincidunt volutpat ligula, sed maximus erat vestibulum et.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryRotation);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        /**
         * 10th trick
         */
        $trick = new Trick();
        $trick->setName('Grabs truck driver');
        $trick->setDescribing(
            'Integer risus dolor, consequat eget turpis id, dapibus ultrices libero. Phasellus non felis in neque sollicitudin lobortis. Phasellus vulputate rutrum orci id imperdiet. In hac habitasse platea dictumst. Nunc et ipsum et mi luctus tincidunt sed at lacus. Aliquam placerat bibendum rutrum.'
        );
        $trick->setImgFilename('');
        $trick->setCategory($categoryGrabs);
        $trick->setCreatedAt(new \DateTime());
        $trick->setModifiedAt(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        $manager->flush();
    }
}
