<?php
    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\AcceptHeader;

    // Dekodowanie json do objektu
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
    use Symfony\Component\Serializer\Serializer;

    // Logger
    use Psr\Log\LoggerInterface;

    // https://symfony.com/doc/current/doctrine/associations.html

    class RelacjeController extends Controller {
        private $logger;
        private $serializer;

        public function __construct(LoggerInterface $logger)
        {
            $this->logger = $logger;

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $this->serializer = new Serializer($normalizers, $encoders);
        }

        /**
         * @Route("/relacje/produkt/{id}")
         * @Method({"GET"})
         */
        public function get_produkt() {
            $produkt = $this->getDoctrine()->getRepository(Produkt::class)
                ->findOneBy(['id' => $id]);

            $serializer = $this->container->get('jms_serializer');
            $response = $serializer->serialize($produkt, 'json');

            return new Response($response, 200, array('Content-Type' => 'application/json'));
        }
    }
?>